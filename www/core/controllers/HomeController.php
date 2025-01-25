<?php

namespace Core\Controllers;

use Core\View;

class HomeController extends Controller
{
    public function index($template, $view): void
    {
        $data = [
            'title' => 'Accueil',
            'message' => ''
        ];

        $this->render($template, $view, $data);
    }

    public function about($template, $view): void
    {
        $data = [
            'title' => 'À propos',
            'content' => ''
        ];

        $this->render($template, $view, $data);
    }

    public function contact($template, $view): void
    {
        $data = [
            'title' => 'Contact',
            'content' => ''
        ];

        $this->render($template, $view, $data);
    }
}
