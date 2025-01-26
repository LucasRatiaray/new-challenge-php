<?php

declare(strict_types=1);

namespace Core\Forms;

use Core\Utils\Csrf;
use Core\Utils\Validation;

class FormBuilder
{
    private array $config;
    private array $errors = [];
    private ?array $userData;

    public function __construct(string $name, array $userData = [])
    {
        $className = "Core\\Forms\\" . $name;

        if (!class_exists($className)) {
            die("Le formulaire " . $name . " n'existe pas dans le namespace Core\\Forms\\");
        }

        $this->config = $className::getConfig($userData);
        $this->userData = $userData;
    }

    public function build(): string
    {
        $html = '';

        // Récupération des configurations du formulaire
        $method = htmlspecialchars($this->config["config"]["method"] ?? "POST", ENT_QUOTES, 'UTF-8');
        $action = htmlspecialchars($this->config["config"]["action"] ?? "", ENT_QUOTES, 'UTF-8');
        $formClasses = htmlspecialchars($this->config["config"]["form_classes"] ?? "", ENT_QUOTES, 'UTF-8');

        // Début du formulaire avec les classes spécifiées
        $html .= "<form class='{$formClasses}' action='{$action}' method='{$method}'>";

        // Champ CSRF
        $html .= Csrf::getTokenInputField();

        // Génération des champs d'entrée avec labels flottants
        if (isset($this->config["inputs"])) {
            foreach ($this->config["inputs"] as $name => $input) {
                $type = htmlspecialchars($input["type"] ?? "text", ENT_QUOTES, 'UTF-8');
                $label = htmlspecialchars($input["label"] ?? ucfirst($name), ENT_QUOTES, 'UTF-8');

                // Récupération des attributs de l'input
                $attributes = $input["attributes"] ?? [];
                $attrString = "";
                foreach ($attributes as $attr => $value) {
                    if (is_bool($value)) {
                        if ($value) {
                            $attrString .= " {$attr}";
                        }
                    } else {
                        // Convertir en chaîne de caractères pour éviter les TypeError
                        $escapedValue = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
                        $attrString .= " {$attr}='{$escapedValue}'";
                    }
                }

                // Gestion spéciale pour les champs de type 'checkbox' avec des options
                if ($type === "checkbox" && isset($input["options"])) {
                    $options = $input["options"];
                    $selected = $input["selected"] ?? [];

                    // Génération des cases à cocher
                    $html .= "<fieldset class='mb-4'>";
                    $html .= "<legend class='text-sm font-medium text-gray-700'>{$label}</legend>";
                    foreach ($options as $option) {
                        // Assurez-vous que chaque option a un 'id' et un 'name'
                        $optionId = htmlspecialchars((string)$option['id'], ENT_QUOTES, 'UTF-8');
                        $optionName = htmlspecialchars($option['name'], ENT_QUOTES, 'UTF-8');
                        $isChecked = in_array($option['id'], $selected) ? "checked" : "";

                        $html .= "<div class='flex items-center mt-2'>";
                        $html .= "<input type='checkbox' id='role_{$optionId}' name='{$attributes['name']}' value='{$optionId}' {$isChecked} class='{$attributes['class']}'>";
                        $html .= "<label for='role_{$optionId}' class='text-sm text-gray-700'>{$optionName}</label>";
                        $html .= "</div>";
                    }
                    $html .= "</fieldset>";
                } else {
                    // Valeur de l'input (pré-remplie si disponible, sauf pour les mots de passe)
                    $value = '';
                    if (isset($this->userData) && isset($this->userData[$name]) && $type !== 'password') {
                        $value = htmlspecialchars((string)$this->userData[$name], ENT_QUOTES, 'UTF-8');
                    } elseif (isset($_POST[$name]) && $type !== 'password') {
                        $value = htmlspecialchars((string)$_POST[$name], ENT_QUOTES, 'UTF-8');
                    }

                    if ($type !== "password") {
                        // Remplacer le placeholder par un espace pour les labels flottants
                        $attrString = preg_replace("/placeholder='[^']*'/", "placeholder=' '", $attrString);
                        $attrString .= " value='{$value}'";
                    }

                    // Récupération des classes du label
                    $labelClasses = htmlspecialchars($input["label_classes"] ?? "", ENT_QUOTES, 'UTF-8');

                    // Génération du HTML pour chaque champ avec label flottant
                    $html .= "<div class='relative mb-5'>";
                    $html .= "<input type='{$type}' {$attrString}>";
                    $html .= "<label for='" . htmlspecialchars($attributes['id'] ?? $name, ENT_QUOTES, 'UTF-8') . "' class='{$labelClasses}'>{$label}</label>";
                    $html .= "</div>";
                }
            }
        }

        // Bouton de soumission
        if (isset($this->config["submit"])) {
            $submitValue = htmlspecialchars($this->config["submit"]["value"] ?? "Envoyer", ENT_QUOTES, 'UTF-8');
            $submitClass = htmlspecialchars($this->config["submit"]["class"] ?? "btn-submit", ENT_QUOTES, 'UTF-8');
            $html .= "<button type='submit' class='{$submitClass}'>{$submitValue}</button>";
        }

        $html .= "</form>";

        return $html;
    }

    public function isSubmitted(): bool
    {
        $method = $this->config["config"]["method"] ?? "POST";
        $methodData = ($method === "POST") ? $_POST : $_GET;

        return !empty($methodData);
    }

    public function isValid(): bool
    {
        $validator = new Validation();
        $method = $this->config["config"]["method"] ?? "POST";
        $data = ($method === "POST") ? $_POST : $_GET;

        if (empty($data['csrf_token'])) {
            $validator->addError("CSRF token manquant.");
        } elseif (!Csrf::verifyToken($data['csrf_token'])) {
            $validator->addError("Le token CSRF est invalide ou expiré.");
        }

        foreach ($this->config["inputs"] as $name => $configField) {
            $value = $data[$name] ?? '';

            // Exemple d'appel au validateur
            if (!empty($configField["attributes"]["required"])) {
                $validator->validateRequired($name, $value);
            }

            if (isset($configField["validation"]["min"]) && isset($configField["validation"]["max"])) {
                $validator->validateLength($name, $value, $configField["validation"]["min"], $configField["validation"]["max"]);
            }

            if ($configField["type"] === "email") {
                $validator->validateEmail($name, $value);
            }
        }

        // Transférer les erreurs au formulaire
        if ($validator->hasErrors()) {
            foreach ($validator->getErrors() as $error) {
                $this->addError($error);
            }
            return false;
        }

        return true;
    }

    public function getData(): array
    {
        $method = $this->config["config"]["method"] ?? "POST";
        $data = ($method === "POST") ? $_POST : $_GET;

        unset($data['csrf_token']);

        // Récupérer les rôles sélectionnés
        if (isset($data['roles']) && is_array($data['roles'])) {
            // Filtrer les rôles pour s'assurer qu'ils sont des entiers
            $data['roles'] = array_map('intval', $data['roles']);

            // Optionnel : Vérifier que les rôles existent réellement dans la base de données
            $roleModel = new \Core\Models\Role();
            $validRoleIds = array_column($roleModel->getAll(), 'id');
            $data['roles'] = array_intersect($data['roles'], $validRoleIds);
        } else {
            $data['roles'] = [];
        }

        return $data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }
}
