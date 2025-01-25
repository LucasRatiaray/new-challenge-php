<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars('Challenge PHP | ' . ($title ?? 'Challenge PHP'), ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen flex flex-col">

    <header>
        <?php include __DIR__ . '/../../layouts/header.php'; ?>
    </header>

    <main class="flex-grow">
        <?= $content ?? '' ?>
    </main>

    <footer class="bg-white rounded-lg shadow-sm m-4">
        <?php include __DIR__ . '/../../layouts/footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.js"></script>
</body>

</html>