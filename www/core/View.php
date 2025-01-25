<?php
declare(strict_types=1);

namespace Core;

class View
{
    public static function render(string $template, string $view, array $data = []): void
    {
        $templateFile = __DIR__ . '/../views/templates/' . $template . '/default.php';

        if (!file_exists($templateFile)) {
            throw new \Exception("Le template {$template} n'existe pas.");
        }

        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("La vue {$view} n'existe pas.");
        }

        // Utiliser des variables sécurisées pour éviter les conflits
        $viewData = $data;

        // Buffering de sortie pour capturer le contenu de la vue
        ob_start();
        extract($viewData, EXTR_SKIP);
        try {
            include $viewFile;
        } catch (\Exception $e) {
            ob_end_clean(); // Nettoyer le buffer en cas d'erreur
            throw $e;
        }
        $content = ob_get_clean();

        // Inclure le template avec le contenu de la vue
        include $templateFile;
    }
}
