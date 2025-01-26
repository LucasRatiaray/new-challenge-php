<?php

declare(strict_types=1);

namespace Core\Utils;

class Validation
{
    private array $errors = [];

    /**
     * Ajoute une erreur à la liste
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Retourne toutes les erreurs
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Vérifie si des erreurs sont présentes
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Valide un champ requis
     */
    public function validateRequired(string $fieldName, $value): void
    {
        if (empty(trim($value))) {
            $this->addError("Le " . strtolower($fieldName) . " est requis.");
        }
    }

    /**
     * Valide la longueur d'une chaîne
     */
    public function validateLength(string $fieldName, string $value, int $min, int $max): void
    {
        $length = strlen($value);
        if ($length < $min || $length > $max) {
            $this->addError("Le " . strtolower($fieldName) . " doit faire entre {$min} et {$max} caractères.");
        }
    }

    /**
     * Valide un email
     */
    public function validateEmail(string $fieldName, string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Erreur lors de création de compte.");
        }
    }

    /**
     * Valide un mot de passe
     */
    public function validatePassword(string $password, string $confirmPassword): void
    {
        if (strlen($password) < 8) {
            $this->addError("Le mot de passe doit faire au moins 8 caractères.");
        }

        if (!preg_match("#[a-zA-Z]#", $password) || !preg_match("#[0-9]#", $password)) {
            $this->addError("Le mot de passe doit contenir au moins une lettre et un chiffre.");
        }

        if ($password !== $confirmPassword) {
            $this->addError("Les mots de passe ne correspondent pas.");
        }
    }

    /**
     * Vérifie si une valeur existe déjà (par exemple, email unique)
     */
    public function validateUnique(string $fieldName, $value, callable $existsInDatabase): void
    {
        $exists = $existsInDatabase($value);

        usleep(500000);

        if ($exists) {
            $this->addError("Erreur lors de création de compte.");
        }
    }
}
