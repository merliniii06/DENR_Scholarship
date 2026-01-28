<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DENR Scholarship') }}</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <nav class="site-nav">
        <div class="nav-container">
            <div class="brand">DENR</div>
        </div>
    </nav>

    <main class="hero">
        <div class="choices">
            <a href="/admin_login" class="choice" title="Admin">
                <img src="{{ asset('Images/Admin.png') }}" alt="Admin">
                <span>Admin</span>
            </a>

            <a href="/apply" class="choice" title="Apply for Scholarship">
                <img src="{{ asset('Images/User.png') }}" alt="Apply for Scholarship">
                <span>Apply</span>
            </a>
        </div>
    </main>
</body>
</html>