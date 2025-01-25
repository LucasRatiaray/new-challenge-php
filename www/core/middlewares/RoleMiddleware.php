<?php

namespace Core\Middlewares;

class RoleMiddleware
{
    private array $requiredRoles;

    public function __construct(array $roles)
    {
        $this->requiredRoles = array_filter($roles, 'is_string');
    }

    public function handle($request, $next)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $sessionRoles = $_SESSION['roles'] ?? [];
        $sessionRoles = is_array($sessionRoles) ? $sessionRoles : [];

        if (empty(array_intersect($this->requiredRoles, $sessionRoles))) {
            http_response_code(403);
            echo "403 Forbidden: Vous n'avez pas les permissions nécessaires.";
            exit;
        }

        return $next($request);
    }
}
