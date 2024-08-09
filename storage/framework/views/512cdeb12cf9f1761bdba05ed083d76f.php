<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Todo List App'); ?></title>
    <link rel="stylesheet" href="/css/bulma.min.css">
    <style>
        html {
            background-color: #f5f5f5;
            user-select: none;
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->yieldContent('scripts'); ?>
    <script src="/js/autoexecs.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>

</body>
</html>

<?php /**PATH /home/lucas/Documents/php/finance/resources/views/layouts/app.blade.php ENDPATH**/ ?>