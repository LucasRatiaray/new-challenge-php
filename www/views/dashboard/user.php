<h3 class="text-3xl font-bold my-8">Gestion des utilisateurs</h3>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Prénom</th>
                <th scope="col" class="px-6 py-3">Nom de Famille</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Rôles</th>
                <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4"><?= htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($user['roles'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="/dashboard/users/edit/<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>" class="font-medium text-blue-600 hover:underline">Modifier</a>
                            |
                            <form action="/dashboard/users/delete/<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <button type="submit" class="font-medium text-red-500 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="bg-white hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>