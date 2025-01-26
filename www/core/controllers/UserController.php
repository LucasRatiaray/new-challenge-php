<?php

declare(strict_types=1);

namespace Core\Controllers;

use Core\Forms\FormBuilder;
use Core\Models\User;
use Core\View;
use Exception;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     */
    public function index(string $template, string $view): void
    {
        $userModel = new User();
        $users = $userModel->getAll();

        $data = [
            'title' => 'Gestion des Utilisateurs',
            'users' => $users,
        ];

        $this->renderWithSharedData($template, $view, $data);
    }

    /**
     * Affiche le formulaire de création d'un utilisateur.
     */
    public function create(string $template, string $view): void
    {
        $userForm = new FormBuilder('UserForm');

        $formHtml = $userForm->build();

        $data = [
            'title' => 'Créer un Utilisateur',
            'formHtml' => $formHtml,
            'errors' => $userForm->getErrors(),
            'action' => 'create'
        ];

        $this->renderWithSharedData($template, $view, $data);
    }

    /**
     * Traite la soumission du formulaire de création d'un utilisateur.
     */
    public function store(string $template, string $view): void
    {
        $userForm = new FormBuilder('UserForm', []);

        if ($userForm->isSubmitted()) {
            if ($userForm->isValid()) {
                $data = $userForm->getData();

                // Vérifier si l'email existe déjà
                $userModel = new User();
                if ($userModel->findByEmail($data['email'])) {
                    $userForm->addError("Un utilisateur avec cet email existe déjà.");
                } else {
                    // Hash du mot de passe
                    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

                    // Préparer les données à créer
                    $createData = [
                        'first_name' => $data['first_name'],
                        'last_name'  => $data['last_name'],
                        'email'      => $data['email'],
                        'password'   => $hashedPassword
                    ];

                    // Créer l'utilisateur et assigner les rôles
                    $userId = $userModel->createWithRoles($createData, $data['roles']);

                    if ($userId) {
                        // Ajouter un message de succès
                        $_SESSION['success_message'] = "Utilisateur créé avec succès.";
                        // Rediriger vers la liste des utilisateurs
                        $this->redirect('/dashboard/users');
                    } else {
                        $userForm->addError("Erreur lors de la création de l'utilisateur.");
                    }
                }
            }
        }

        $formHtml = $userForm->build();

        $data = [
            'title'    => 'Créer un Utilisateur',
            'formHtml' => $formHtml,
            'errors'   => $userForm->getErrors(),
            'action'   => 'create'
        ];

        View::render($template, $view, $data);
    }


    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     */
    public function edit(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID utilisateur invalide.";
            exit;
        }

        $userId = (int)$params['id'];

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur non trouvé.";
            exit;
        }

        $userForm = new FormBuilder('UserForm', $user);

        $formHtml = $userForm->build();

        $data = [
            'title' => 'Modifier un Utilisateur',
            'formHtml' => $formHtml,
            'errors' => $userForm->getErrors(),
            'action' => 'edit',
            'user' => $user
        ];

        $this->renderWithSharedData($template, $view, $data);
    }

    /**
     * Traite la soumission du formulaire d'édition d'un utilisateur.
     */
    public function update(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID utilisateur invalide.";
            exit;
        }

        $userId = (int)$params['id'];

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur non trouvé.";
            exit;
        }

        $userForm = new FormBuilder('UserForm', $user);

        if ($userForm->isSubmitted()) {
            if ($userForm->isValid()) {
                $data = $userForm->getData();

                // Vérifier si l'email est modifié et existe déjà
                if ($data['email'] !== $user['email'] && $userModel->findByEmail($data['email'])) {
                    $userForm->addError("Un utilisateur avec cet email existe déjà.");
                } else {
                    // Préparer les données à mettre à jour
                    $updateData = [
                        'id'         => $userId, // Assurez-vous que l'ID est inclus
                        'first_name' => $data['first_name'],
                        'last_name'  => $data['last_name'],
                        'email'      => $data['email']
                    ];

                    // Si un nouveau mot de passe est fourni, le hash et l'ajouter
                    if (!empty($data['password'])) {
                        $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                    }

                    // Mettre à jour l'utilisateur et ses rôles
                    $updated = $userModel->updateWithRoles($updateData, $data['roles']);

                    if ($updated) {
                        // Ajouter un message de succès
                        $_SESSION['success_message'] = "Utilisateur mis à jour avec succès.";
                        // Rediriger vers la liste des utilisateurs
                        $this->redirect('/dashboard/users');
                    } else {
                        $userForm->addError("Erreur lors de la mise à jour de l'utilisateur.");
                    }
                }
            }
        }

        $formHtml = $userForm->build();

        $data = [
            'title'    => 'Modifier un Utilisateur',
            'formHtml' => $formHtml,
            'errors'   => $userForm->getErrors(),
            'action'   => 'edit',
            'user'     => $user
        ];

        View::render($template, $view, $data);
    }

    /**
     * Traite la suppression d'un utilisateur.
     */
    public function delete(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID utilisateur invalide.";
            exit;
        }

        $userId = (int)$params['id'];

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur non trouvé.";
            exit;
        }

        // Supprimer l'utilisateur
        $deleted = $userModel->delete($userId);

        if ($deleted) {
            $_SESSION['success_message'] = "Utilisateur supprimé avec succès.";
            $this->redirect('/dashboard/users');
        } else {
            echo "Erreur lors de la suppression de l'utilisateur.";
        }
    }
}
