<?php

declare(strict_types=1);

namespace Core\Controllers;

use Core\View;
use Core\Utils\Database;

abstract class Controller
{
    /**
     * Méthode pour rendre une vue
     */
    protected function render(string $template, string $view, array $data = []): void
    {
        View::render($template, $view, $data);
    }

    /**
     * Méthode pour rediriger
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Récupère les données globales pour tous les contrôleurs
     */
    protected function getSharedData(): array
    {
        $db = Database::getInstance();

        // Récupérer le nombre d'utilisateurs
        $stmt = $db->query("SELECT COUNT(*) as total_users FROM users");
        $totalUsers = $stmt->fetch()['total_users'];

        // Récupérer le nombre de pages
        $stmt = $db->query("SELECT COUNT(*) as total_pages FROM pages");
        $totalPages = $stmt->fetch()['total_pages'];

        return [
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
        ];
    }

    /**
     * Méthode pour rendre une vue avec les données globales
     */
    protected function renderWithSharedData(string $template, string $view, array $data = []): void
    {
        $sharedData = $this->getSharedData();
        $this->render($template, $view, array_merge($sharedData, $data));
    }
}
