<?php

namespace Core\Middlewares;

use Core\Utils\Database;

class DashboardDataMiddleware
{
    public static function getSharedData(): array
    {
        $db = Database::getInstance();

        // Nombre d'utilisateurs
        $stmt = $db->query("SELECT COUNT(*) as total_users FROM users");
        $totalUsers = $stmt->fetch()['total_users'];

        // Nombre de pages
        $stmt = $db->query("SELECT COUNT(*) as total_pages FROM pages");
        $totalPages = $stmt->fetch()['total_pages'];

        return [
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
        ];
    }
}
