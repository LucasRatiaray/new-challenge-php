<?php
declare(strict_types=1);

namespace Core\Utils;

class Csrf
{
    public static function getTokenInputField(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token']) || $_SESSION['csrf_token_time'] < time() - 3600) { // 1 heure de validité
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        return "<input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>";
    }

    public static function verifyToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            // Optionnel : Décommenter la ligne suivante pour une utilisation à sens unique des tokens
            // unset($_SESSION['csrf_token']);
            return true;
        }
        return false;
    }
}
