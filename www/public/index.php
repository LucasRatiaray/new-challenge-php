<?php
declare(strict_types=1);

use Core\Router;

require_once __DIR__ . '/../bootstrap/autoload.php';

// Démarrage de la session avec des paramètres sécurisés
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Assurez-vous que HTTPS est utilisé
        'cookie_samesite' => 'Strict',
    ]);
}

$routes = require __DIR__ . '/../core/config/routes.php'; 

$router = new Router($routes);

try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    error_log($e->getMessage()); // Log l'erreur pour les développeurs
    http_response_code(500);
    // Afficher une vue d'erreur générique
    // Assurez-vous que la vue d'erreur 500 existe dans vos vues
    echo "Une erreur interne est survenue. Veuillez réessayer plus tard.";
}
