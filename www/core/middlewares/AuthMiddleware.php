<?php

declare(strict_types=1);

namespace Core\Middlewares;

class AuthMiddleware
{
    public function handle($data, $next)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        return $next($data);
    }
}
