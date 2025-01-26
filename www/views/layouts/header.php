<?php

use Core\Models\Page;

$pageModel = new Page();
$pages = $pageModel->getAll();

?>

<nav class="bg-white border-gray-200">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 text-blue-700" viewBox="0 0 1024 1024">
                <path fill="currentColor" d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32M513.1 518.1l-192 161c-5.2 4.4-13.1.7-13.1-6.1v-62.7c0-2.3 1.1-4.6 2.9-6.1L420.7 512l-109.8-92.2a7.63 7.63 0 0 1-2.9-6.1V351c0-6.8 7.9-10.5 13.1-6.1l192 160.9c3.9 3.2 3.9 9.1 0 12.3M716 673c0 4.4-3.4 8-7.5 8h-185c-4.1 0-7.5-3.6-7.5-8v-48c0-4.4 3.4-8 7.5-8h185c4.1 0 7.5 3.6 7.5 8z" />
            </svg>
            <span class="self-center text-2xl font-semibold whitespace-nowrap">Challenge PHP</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <?php if (!isset($_SESSION['user_id'])) : ?>
                <a href="/login" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Se connecter</a>
            <?php elseif (isset($_SESSION['roles']) && (in_array('ADMIN', $_SESSION['roles'], true) || in_array('EDITOR', $_SESSION['roles'], true))) : ?>
                <a href="/dashboard" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Tableau de bord</a>
            <?php else : ?>
                <a href="/logout" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Déconnexion</a>
            <?php endif; ?>
            <button data-collapse-toggle="navbar-cta" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-cta" aria-expanded="false">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                <?php
                $current_page = basename($_SERVER['REQUEST_URI']);
                ?>
                <li>
                    <a href="/" class="block py-2 px-3 md:p-0 rounded-sm <?php echo $current_page == '' || $current_page == 'index.php' ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700'; ?>" aria-current="page">Accueil</a>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li>
                        <a href="/<?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?>"
                            class="block py-2 px-3 md:p-0 rounded-sm text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700">
                            <?= htmlspecialchars(ucfirst($page['title']), ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="/about" class="block py-2 px-3 md:p-0 rounded-sm <?php echo $current_page == 'about' ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700'; ?>">À propos</a>
                </li>
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <li>
                        <a href="/register" class="block py-2 px-3 md:p-0 rounded-sm <?php echo $current_page == 'register' ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700'; ?>">Créer un compte</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>