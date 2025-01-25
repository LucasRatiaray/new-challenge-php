<div class="container mx-auto p-4">
    <h1 class="flex justify-center my-8 items-center text-5xl font-extrabold text-black">
        Challenge
        <span class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2.5 py-0.5 rounded-sm ms-2">
            PHP
        </span>
        <small class="ms-2 font-semibold text-gray-500 dark:text-gray-400">| Inscrivez-vous</small>
    </h1>
    <?php
    echo $formHtml;
    ?>
    <div class="flex justify-center mt-12">
        <a href="/login" class="text-blue-700 hover:underline">Déjà un compte?</a>

        <span class="mx-2 text-gray-500 dark:text-gray-400">|</span>

        <a href="/" class="text-blue-700 hover:underline">Retour à l'accueil</a>
    </div>
</div>