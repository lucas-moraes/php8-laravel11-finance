<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Todo List App')</title>
    <link rel="stylesheet" href="/css/bulma.min.css">
    <style>
        html {
            background-color: #f5f5f5;
            user-select: none;
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('components.navbar')
    <div class="container">
        @yield('content')
    </div>
    @yield('scripts')
    <script src="/js/autoexecs.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>

</body>
</html>

