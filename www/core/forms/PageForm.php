<?php
declare(strict_types=1);

namespace Core\Forms;

class PageForm
{
    public static function getConfig(array $page = []): array
    {
        $isEdit = isset($page['id']);

        return [
            'config' => [
                'action' => $isEdit ? "/dashboard/pages/update/{$page['id']}" : '/dashboard/pages/store',
                'method' => 'POST',
                'submit' => $isEdit ? 'Mettre à jour' : 'Créer',
                'form_classes' => 'max-w-md mx-auto'
            ],
            'inputs' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Titre',
                    'attributes' => [
                        'id' => 'title',
                        'name' => 'title',
                        'placeholder' => ' ',
                        'required' => true,
                        'class' => 'block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    ],
                    'label_classes' => 'absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1',
                    'value' => $page['title'] ?? '',
                    'validation' => [
                        'min' => 2,
                        'max' => 100,
                        'error' => 'Le titre doit contenir entre 2 et 100 caractères.'
                    ]
                ],
                'slug' => [
                    'type' => 'text',
                    'label' => 'Slug (URL)',
                    'attributes' => [
                        'id' => 'slug',
                        'name' => 'slug',
                        'placeholder' => ' ',
                        'required' => true,
                        'class' => 'block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    ],
                    'label_classes' => 'absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1',
                    'value' => $page['slug'] ?? '',
                    'validation' => [
                        'min' => 2,
                        'max' => 100,
                        'error' => 'Le slug doit contenir entre 2 et 100 caractères.'
                    ]
                ],
                'content' => [
                    'type' => 'textarea',
                    'label' => 'Contenu',
                    'attributes' => [
                        'id' => 'content',
                        'name' => 'content',
                        'rows' => 5,
                        'placeholder' => ' ',
                        'required' => true,
                        'class' => 'block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    ],
                    'label_classes' => 'absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-100 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1',
                    'value' => $page['content'] ?? '',
                    'validation' => [
                        'min' => 10,
                        'error' => 'Le contenu doit contenir au moins 10 caractères.'
                    ]
                ],
                'is_active' => [
                    'type' => 'checkbox',
                    'label' => 'Activer la page',
                    'attributes' => [
                        'id' => 'is_active',
                        'name' => 'is_active',
                        'value' => 1,
                        'checked' => isset($page['is_active']) && $page['is_active'],
                        'class' => 'mr-2 leading-tight'
                    ],
                    'label_classes' => 'inline-flex items-center mr-4 text-sm text-gray-700',
                    'validation' => [
                        'required' => false,
                        'error' => 'Veuillez sélectionner cette option pour activer la page.'
                    ]
                ]
            ],
            'submit' => [
                'value' => $isEdit ? 'Mettre à jour' : 'Créer',
                'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center'
            ]
        ];
    }
}
