<?php

declare(strict_types=1);

namespace Core;

use Core\Middlewares\AuthMiddleware;
use Core\Middlewares\RoleMiddleware;

class Router
{
    protected array $routes = [];
    protected array $routePatterns = [];

    public function __construct(array $routes)
    {
        foreach ($routes as $path => $route) {
            $this->addRoute($path, $route);
        }
    }

    protected function addRoute(string $path, array $route): void
    {
        // Convertir les segments dynamiques en regex
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'pattern' => $pattern,
            'route' => $route
        ];
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH);

        foreach ($this->routes as $routeEntry) {
            if (preg_match($routeEntry['pattern'], $path, $matches)) {
                $route = $routeEntry['route'];

                // Vérifier la méthode HTTP
                $allowedMethods = $route['methods'] ?? ['GET'];
                if (!in_array($requestMethod, $allowedMethods)) {
                    http_response_code(405);
                    echo "405 Method Not Allowed";
                    exit;
                }

                // Récupérer les données en fonction de la méthode
                $data = ($requestMethod === 'POST') ? $_POST : $_GET;

                // Gestion des middlewares
                if (!empty($route['security']) && $route['security'] === true) {
                    $authMiddleware = new AuthMiddleware();
                    $authMiddleware->handle($data, function ($data) {
                        // Middleware Auth passé avec succès
                        return $data;
                    });

                    if (isset($route['roles']) && is_array($route['roles'])) {
                        $roleMiddleware = new RoleMiddleware($route['roles']);
                        $roleMiddleware->handle($data, function ($data) {
                            // Middleware Rôles passé avec succès
                            return $data;
                        });
                    }
                }


                // Extraire les paramètres dynamiques
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $controllerName = 'Core\\Controllers\\' . $route['controller'];
                $actionName = $route['action'];

                if (!class_exists($controllerName)) {
                    throw new \Exception("Contrôleur {$controllerName} introuvable");
                }

                $controller = new $controllerName;

                if (!method_exists($controller, $actionName)) {
                    throw new \Exception("Méthode {$actionName} introuvable dans {$controllerName}");
                }

                // Appeler l'action du contrôleur en passant les paramètres dynamiques
                $controller->$actionName(
                    $route['template'],
                    $route['view'],
                    $params
                );

                // Route trouvée et traitée, sortir de la méthode
                return;
            }
        }

        // Si aucune route ne correspond
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }
}
