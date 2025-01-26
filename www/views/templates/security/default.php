<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars('Challenge PHP | ' . ($title ?? 'Challenge PHP'), ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

     <?php if (!empty($errors)): ?>
        <div class='absolute top-8 right-8 space-y-4'>
            <?php foreach ($errors as $error): ?>
                <div id='toast-error' class='flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm' role='alert'>
                    <div class='inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg'>
                        <svg class='w-5 h-5' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
                            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z' />
                        </svg>
                    </div>
                    <div class='ms-3 text-sm font-normal'>
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <button type='button' class='ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8' data-dismiss-target='#toast-error' aria-label='Close' onclick='this.parentElement.style.display="none";'>
                        <span class='sr-only'>Close</span>
                        <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                            <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6' />
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <main>
        <?= $content ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.3/dist/flowbite.min.js"></script>
</body>

</html>
