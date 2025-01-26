<?php

declare(strict_types=1);

namespace Core\Forms;

class RegisterForm
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "/register",
                "method" => "POST",
                "submit" => "S'inscrire",
                "form_classes" => "max-w-sm mx-auto"
            ],
            "inputs" => [
                "last_name" => [
                    "type" => "text",
                    "label" => "Votre nom",
                    "attributes" => [
                        "id" => "last_name",
                        "name" => "last_name",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                ],
                "first_name" => [
                    "type" => "text",
                    "label" => "Votre prénom",
                    "attributes" => [
                        "id" => "first_name",
                        "name" => "first_name",
                        "placeholder" => " ",
                        "required" => false,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                ],
                "email" => [
                    "type" => "email",
                    "label" => "Votre email",
                    "attributes" => [
                        "id" => "email",
                        "name" => "email",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                ],
                "password" => [
                    "type" => "password",
                    "label" => "Votre mot de passe",
                    "attributes" => [
                        "id" => "password",
                        "name" => "password",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                ],
                "confirm_password" => [
                    "type" => "password",
                    "label" => "Confirmation du mot de passe",
                    "attributes" => [
                        "id" => "confirm_password",
                        "name" => "confirm_password",
                        "placeholder" => " ",
                        "required" => true,
                        "class" => "block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    ],
                    "label_classes" => "absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1",
                ]
            ],
            "submit" => [
                "value" => "S'inscrire",
                "class" => "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
            ]
        ];
    }
}
