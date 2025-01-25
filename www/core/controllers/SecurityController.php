<?php
declare(strict_types=1);

namespace Core\Controllers;

use Core\Forms\FormBuilder;
use Core\Utils\Database;
use Core\Models\User;
use Core\Utils\Mailer;
use Exception;

class SecurityController extends Controller
{
    public function login(string $template, string $view): void
    {
        $loginForm = new FormBuilder('LoginForm');

        if ($loginForm->isSubmitted()) {
            if ($loginForm->isValid()) {
                $data = $loginForm->getData();

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT id, first_name, last_name, email, password, is_activated FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $data['email']]);
                $userData = $stmt->fetch();

                if ($userData) {
                    if (!$userData['is_activated']) {
                        $loginForm->addError("Votre compte n'est pas encore activé. Veuillez vérifier votre email.");
                    } elseif (password_verify($data['password'], $userData['password'])) {
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
                        $loginForm->addError("Email ou mot de passe incorrect.");
                    }
                } else {
                    $loginForm->addError("Email ou mot de passe incorrect.");
                }
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

        if ($registerForm->isSubmitted()) {
            if ($registerForm->isValid()) {
                $data = $registerForm->getData();

                if ($data['password'] !== $data['confirm_password']) {
                    $registerForm->addError("Les mots de passe ne correspondent pas.");
                } else {
                    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

                    $db = Database::getInstance();

                    try {
                        $db->beginTransaction();

                        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                        $stmt->execute(['email' => $data['email']]);
                        if ($stmt->fetch()) {
                            throw new Exception("Un utilisateur avec cet email existe déjà.");
                        }

                        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, is_activated) VALUES (:first_name, :last_name, :email, :password, :is_activated)");
                        $stmt->execute([
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'email' => $data['email'],
                            'password' => $hashedPassword,
                            'is_activated' => false // Le compte n'est pas activé par défaut
                        ]);

                        $userId = (int)$db->lastInsertId();

                        // Générer un lien d'activation unique
                        $activationToken = bin2hex(random_bytes(32));
                        $activationLink = "http://localhost/activate?token={$activationToken}";

                        // Sauvegarder le token dans la base de données
                        $stmt = $db->prepare("INSERT INTO user_activations (user_id, token) VALUES (:user_id, :token)");
                        $stmt->execute([
                            'user_id' => $userId,
                            'token' => $activationToken
                        ]);

                        // Envoyer l'email d'activation
                        $mailer = new Mailer();
                        if (!$mailer->sendActivationEmail($data['email'], $activationLink)) {
                            throw new Exception("Erreur lors de l'envoi de l'email d'activation.");
                        }

                        $db->commit();

                        $_SESSION['success_message'] = "Inscription réussie. Veuillez vérifier votre email pour activer votre compte.";
                        header("Location: /login");
                        exit;
                    } catch (Exception $e) {
                        $db->rollBack();
                        $registerForm->addError("Erreur lors de l'inscription : " . $e->getMessage());
                    }
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

    public function activate(string $template, string $view): void
    {
        $token = $_GET['token'] ?? null;

        if ($token) {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT user_id FROM user_activations WHERE token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            $activation = $stmt->fetch();

            if ($activation) {
                $userId = $activation['user_id'];

                $stmt = $db->prepare("UPDATE users SET is_activated = TRUE WHERE id = :user_id");
                $stmt->execute(['user_id' => $userId]);

                $stmt = $db->prepare("DELETE FROM user_activations WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $userId]);

                $_SESSION['success_message'] = "Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.";
                header("Location: /login");
                exit;
            } else {
                echo "Lien d'activation invalide ou expiré.";
            }
        } else {
            echo "Lien d'activation manquant.";
        }
    }

    public function dashboard(string $template, string $view): void
    {
        $this->render($template, $view, [
            'title' => 'Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'email' => $_SESSION['email']
        ]);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header("Location: /");
        exit;
    }
}
