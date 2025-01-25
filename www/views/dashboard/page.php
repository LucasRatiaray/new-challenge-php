<h3 class="text-3xl font-bold my-8">Gestion des pages</h3>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Titre</th>
                <th scope="col" class="px-6 py-3">Slug</th>
                <th scope="col" class="px-6 py-3">Date de création</th>
                <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pages)): ?>
                <?php foreach ($pages as $page): ?>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4"><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($page['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="/dashboard/pages/update/<?= htmlspecialchars($page['id'], ENT_QUOTES, 'UTF-8') ?>" class="font-medium text-blue-600 hover:underline">Modifier</a>
                            |
                            <form action="/dashboard/pages/delete/<?= htmlspecialchars($page['id'], ENT_QUOTES, 'UTF-8') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette page ?');">
                                <button type="submit" class="font-medium text-red-500 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="bg-white hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center">Aucune page trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>