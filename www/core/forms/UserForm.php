<?php
declare(strict_types=1);

namespace Core\Forms;

use Core\Models\Role;

class UserForm
{
    public static function getConfig(array $user = []): array
    {
        $isEdit = isset($user['id']);

        // Récupérer tous les rôles disponibles
        $roleModel = new Role();
        $roles = $roleModel->getAll();

        // Récupérer les rôles assignés si en édition
        $assignedRoleIds = [];
        if ($isEdit) {
            $assignedRoles = $roleModel->getUserRoles($user['id']);
            $assignedRoleIds = array_column($assignedRoles, 'id');
        }

        return [
            "config" => [
                "action" => $isEdit ? "/dashboard/users/update/{$user['id']}" : "/dashboard/users/store",
                "method" => "POST",
                "submit" => $isEdit ? "Mettre à Jour" : "Créer",
                "form_classes" => "max-w-md mx-auto"
            ],
            "inputs" => [
                "first_name" => [
                    "type" => "text",
                    "label" => "Prénom",
                    "attributes" => [
                        "id" => "first_name",
                        "name" => "first_name",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                    "validation" => [
                        "min" => 2,
                        "max" => 50,
                        "error" => "Le prénom doit contenir entre 2 et 50 caractères."
                    ]
                ],
                "last_name" => [
                    "type" => "text",
                    "label" => "Nom de Famille",
                    "attributes" => [
                        "id" => "last_name",
                        "name" => "last_name",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                    "validation" => [
                        "min" => 2,
                        "max" => 50,
                        "error" => "Le nom de famille doit contenir entre 2 et 50 caractères."
                    ]
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Email",
                    "attributes" => [
                        "id" => "email",
                        "name" => "email",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                    "validation" => [
                        "min" => 8,
                        "max" => 320,
                        "error" => "L'email doit contenir entre 8 et 320 caractères et être valide."
                    ]
                ],
                "password" => [
                    "type" => "password",
                    "label" => "Mot de Passe",
                    "attributes" => [
                        "id" => "password",
                        "name" => "password",
                        "placeholder" => " ",
                        "required" => !$isEdit, // Requis uniquement lors de la création
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                    "validation" => [
                        "min" => 8,
                        "error" => "Le mot de passe doit contenir au moins 8 caractères, incluant des lettres et des chiffres."
                    ]
                ],
                "confirm_password" => [
                    "type" => "password",
                    "label" => "Confirmer le Mot de Passe",
                    "attributes" => [
                        "id" => "confirm_password",
                        "name" => "confirm_password",
                        "placeholder" => " ",
                        "required" => !$isEdit, // Requis uniquement lors de la création
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                    "validation" => [
                        "min" => 8,
                        "error" => "La confirmation du mot de passe doit correspondre au mot de passe."
                    ]
                ],
                "roles" => [ // Nouveau champ pour les rôles
                    "type" => "checkbox",
                    "label" => "Rôles",
                    "options" => $roles, // Liste des rôles disponibles
                    "selected" => $isEdit ? $assignedRoleIds : [], // Rôles assignés lors de l'édition
                    "attributes" => [
                        "id" => "roles",
                        "name" => "roles[]", // Utiliser des crochets pour les inputs multiples
                        "required" => false, // Non requis
                        "class" => "mr-2 leading-tight"
                    ],
                    "label_classes" => "inline-flex items-center mr-4 text-sm text-gray-700",
                    "validation" => [
                        "required" => false,
                        "error" => "Veuillez sélectionner au moins un rôle."
                    ]
                ]
            ],
            "submit" => [
                "value" => $isEdit ? "Mettre à Jour" : "Créer",
                "class" => "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
            ]
        ];
    }
}
