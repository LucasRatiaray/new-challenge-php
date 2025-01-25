<div class="container mx-auto p-4">
    <h3 class="flex justify-center my-8 items-center text-3xl font-extrabold text-black">
        <?php if (isset($action) && $action === 'edit'): ?>
            Modifier un Utilisateur <span class="text-blue-700 pl-2"><?= htmlspecialchars(ucfirst($user['first_name']), ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars(ucfirst($user['last_name']), ENT_QUOTES, 'UTF-8') ?></span>
        <?php else: ?>
            Créer un Utilisateur
        <?php endif; ?>
    </h3>
    <?php
    echo $formHtml;
    ?>
</div>