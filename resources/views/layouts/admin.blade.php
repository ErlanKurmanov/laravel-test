<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Панель администратора')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Дополнительные стили при необходимости -->
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <!-- Боковое меню (опционально) -->
    <nav class="bg-light border-end" style="width: 250px; min-height: 100vh; padding-top: 1rem;">
        <div class="nav flex-column nav-pills px-3">
            <a class="nav-link" href="{{ route('admin.tickets.index') }}">Заявки</a>
            <!-- Другие пункты меню -->
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="flex-grow-1 p-4">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS (опционально, если нужны интерактивные компоненты) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
