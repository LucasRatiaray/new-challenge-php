<div class="container mx-auto p-4">
    <h3 class="flex justify-center my-8 items-center text-3xl font-extrabold text-black">
        <?php if (isset($action) && $action === 'edit'): ?>
            Modifier une Page <span class="text-blue-700 pl-2"><?= htmlspecialchars(ucfirst($page['title']), ENT_QUOTES, 'UTF-8') ?></span>
        <?php else: ?>
            Créer une Page
        <?php endif; ?>
    </h3>
    <?php
    echo $formHtml;
    ?>
</div>