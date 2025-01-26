<?php

declare(strict_types=1);

namespace Core\Controllers;

use Core\Forms\FormBuilder;
use Core\Utils\Database;
use Core\Utils\Validation;
use Core\Models\User;
use Core\Models\Role;
use Exception;

class SecurityController extends Controller
{
    public function login(string $template, string $view): void
    {
        $loginForm = new FormBuilder('LoginForm');
        $validator = new Validation();

        if ($loginForm->isSubmitted()) {
            $data = $loginForm->getData();

            // Validation des champs
            $validator->validateEmail('email', $data['email'] ?? '');
            $validator->validateRequired('mot de passe', $data['password'] ?? '');

            if (!$validator->hasErrors()) {
                $db = Database::getInstance();

                try {
                    // Vérification de l'utilisateur dans la base de données
                    $stmt = $db->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = :email LIMIT 1");
                    $stmt->execute(['email' => $data['email']]);
                    $userData = $stmt->fetch();

                    if ($userData && password_verify($data['password'], $userData['password'])) {
                        // Création de l'utilisateur en session
                        $user = new User($userData);
                        $user->getRoles();

                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['first_name'] = $user->first_name;
                        $_SESSION['last_name'] = $user->last_name;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['roles'] = $user->roles;

                        header("Location: /dashboard");
                        exit;
                    } else {
                        $validator->addError("Email ou mot de passe incorrect.");
                    }
                } catch (Exception $e) {
                    $validator->addError("Une erreur est survenue lors de la connexion. Veuillez réessayer.");
                }
            }

            // Ajout des erreurs au formulaire
            foreach ($validator->getErrors() as $error) {
                $loginForm->addError($error);
            }
        }

        $formHtml = $loginForm->build();

        $this->render($template, $view, [
            'title' => 'Connexion',
            'formHtml' => $formHtml,
            'errors' => $loginForm->getErrors()
        ]);
    }

    public function register(string $template, string $view): void
    {
        $registerForm = new FormBuilder('RegisterForm');
        $validator = new Validation();

        if ($registerForm->isSubmitted()) {
            $data = $registerForm->getData();

            // Validation des champs avec Validation.php
            $validator->validateRequired('Nom', $data['last_name']);
            $validator->validateLength('Nom', $data['last_name'], 2, 100);

            $validator->validateRequired('Prénom', $data['first_name']);
            $validator->validateLength('Prénom', $data['first_name'], 2, 100);

            $validator->validateEmail('Email', $data['email']);
            $validator->validateUnique('Email', $data['email'], function ($email) {
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $email]);
                return $stmt->fetch() !== false;
            });

            $validator->validatePassword($data['password'], $data['confirm_password']);

            // Si des erreurs sont présentes, les ajouter au formulaire
            if ($validator->hasErrors()) {
                foreach ($validator->getErrors() as $error) {
                    $registerForm->addError($error);
                }
            } else {
                // Aucun problème, continuer avec l'inscription
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $db = Database::getInstance();

                try {
                    $db->beginTransaction();

                    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
                    $stmt->execute([
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'],
                        'password' => $hashedPassword
                    ]);

                    $db->commit();

                    // Récupérer le nouvel utilisateur
                    $newUser = new User();
                    $newUser->findByEmail($data['email']);

                    // Assigner le rôle par défaut VIEWER
                    $role = new Role();
                    $roleName = $role->findByName('VIEWER');

                    if ($roleName) {
                        $role->assignRoles($newUser->id, [$roleName['id']]);
                    }

                    // Stocker les informations de l'utilisateur en session
                    $_SESSION['user_id'] = $newUser->id;
                    $_SESSION['first_name'] = $newUser->first_name;
                    $_SESSION['last_name'] = $newUser->last_name;
                    $_SESSION['email'] = $newUser->email;
                    $_SESSION['roles'] = $newUser->roles;

                    // Déterminer la redirection en fonction des rôles
                    $roleNames = array_column($newUser->roles, 'name');

                    if (in_array('EDITOR', $roleNames, true) || in_array('ADMIN', $roleNames, true)) {
                        header("Location: /dashboard");
                        exit;
                    }

                    header("Location: /");
                    exit;
                } catch (Exception $e) {
                    $db->rollBack();
                    $registerForm->addError("Erreur lors de l'inscription, veuillez réessayer.");
                }
            }
        }

        $formHtml = $registerForm->build();

        $this->render($template, $view, [
            'title' => 'Inscription',
            'formHtml' => $formHtml,
            'errors' => $registerForm->getErrors()
        ]);
    }

    public function dashboard(string $template, string $view): void
    {
        // La vérification de l'authentification est gérée par le middleware

        $data = [
            'title' => 'Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'email' => $_SESSION['email']
        ];

        $this->renderWithSharedData($template, $view, $data);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header("Location: /");
        exit;
    }
}
