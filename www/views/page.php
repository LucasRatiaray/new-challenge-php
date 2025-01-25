<article class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="prose">
        <?= $content ?>
    </div>
</article>
