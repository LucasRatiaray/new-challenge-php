<?php

declare(strict_types=1);

namespace Core\Controllers;

use Core\Forms\FormBuilder;
use Core\Models\Page;
use Core\View;
use Exception;

class PageController extends Controller
{
    public function index(string $template, string $view): void
    {
        $pageModel = new Page();
        $pages = $pageModel->getAll();

        $data = [
            'title' => 'Gestion des Pages',
            'pages' => $pages
        ];

        $this->render($template, $view, $data);
    }

    public function show(string $template, string $view, array $params): void
    {
        $pageModel = new Page();

        // Vérifie si le slug est transmis
        if (!isset($params['slug'])) {
            http_response_code(404);
            echo "Page introuvable.";
            exit;
        }

        // Recherche la page par son slug
        $page = $pageModel->findBySlug($params['slug']);

        // Si la page n'existe pas ou est inactive
        if (!$page || !$page['is_active']) {
            http_response_code(404);
            echo "Page introuvable ou désactivée.";
            exit;
        }

        // Prépare les données pour la vue
        $data = [
            'title' => $page['title'],
            'content' => $page['content']
        ];

        // Rendu avec le template et la vue
        View::render($template, $view, $data);
    }

    public function create(string $template, string $view): void
    {
        $pageForm = new FormBuilder('PageForm');

        $formHtml = $pageForm->build();

        $data = [
            'title' => 'Créer une Page',
            'formHtml' => $formHtml,
            'errors' => $pageForm->getErrors(),
            'action' => 'create'
        ];

        $this->render($template, $view, $data);
    }

    public function store(string $template, string $view): void
    {
        $pageForm = new FormBuilder('PageForm', []);

        if ($pageForm->isSubmitted()) {
            if ($pageForm->isValid()) {
                $data = $pageForm->getData();
                $pageModel = new Page();

                if ($pageModel->create($data)) {
                    $_SESSION['success_message'] = "Page créée avec succès.";
                    $this->redirect('/dashboard/pages');
                } else {
                    $pageForm->addError("Erreur lors de la création de la page.");
                }
            }
        }

        $formHtml = $pageForm->build();

        $data = [
            'title' => 'Créer une Page',
            'formHtml' => $formHtml,
            'errors' => $pageForm->getErrors(),
            'action' => 'create'
        ];

        View::render($template, $view, $data);
    }

    public function edit(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID page invalide.";
            exit;
        }

        $pageId = (int)$params['id'];

        $pageModel = new Page();
        $page = $pageModel->findById($pageId);

        if (!$page) {
            http_response_code(404);
            echo "Page non trouvée.";
            exit;
        }

        $pageForm = new FormBuilder('PageForm', $page);

        $formHtml = $pageForm->build();

        $data = [
            'title' => 'Modifier une Page',
            'formHtml' => $formHtml,
            'errors' => $pageForm->getErrors(),
            'action' => 'edit',
            'page' => $page
        ];

        $this->render($template, $view, $data);
    }

    public function update(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID page invalide.";
            exit;
        }

        $pageId = (int)$params['id'];

        $pageModel = new Page();
        $page = $pageModel->findById($pageId);

        if (!$page) {
            http_response_code(404);
            echo "Page non trouvée.";
            exit;
        }

        $pageForm = new FormBuilder('PageForm', $page);

        if ($pageForm->isSubmitted()) {
            if ($pageForm->isValid()) {
                $data = $pageForm->getData();

                if ($pageModel->update($pageId, $data)) {
                    $_SESSION['success_message'] = "Page mise à jour avec succès.";
                    $this->redirect('/dashboard/pages');
                } else {
                    $pageForm->addError("Erreur lors de la mise à jour de la page.");
                }
            }
        }

        $formHtml = $pageForm->build();

        $data = [
            'title' => 'Modifier une Page',
            'formHtml' => $formHtml,
            'errors' => $pageForm->getErrors(),
            'action' => 'edit',
            'page' => $page
        ];

        View::render($template, $view, $data);
    }

    public function delete(string $template, string $view, array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            http_response_code(400);
            echo "Bad Request: ID page invalide.";
            exit;
        }

        $pageId = (int)$params['id'];

        $pageModel = new Page();
        $page = $pageModel->findById($pageId);

        if (!$page) {
            http_response_code(404);
            echo "Page non trouvée.";
            exit;
        }

        if ($pageModel->delete($pageId)) {
            $this->redirect('/dashboard/pages');
        } else {
            echo "Erreur lors de la suppression de la page.";
        }
    }
}
