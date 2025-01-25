<?php
declare(strict_types=1);

namespace Core\Controllers;

use Core\View;

abstract class Controller
{
    protected function render(string $template, string $view, array $data = []): void
    {
        View::render($template, $view, $data);
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
