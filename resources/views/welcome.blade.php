<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DENR Scholarship') }}</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-xl w-full p-6 bg-white rounded shadow">
        <div style="display:flex;justify-content:center;margin-bottom:1rem;">
            <a href="{{ url('/home') }}" title="Go to Home">
                <img src="{{ asset('Images/Frame 1.png') }}" alt="Center Image" style="max-width:220px;height:auto;cursor:pointer;">
            </a>
        </div>
        <h1 class="text-2xl font-bold mb-2">Welcome to DENR</h1>
        <p class="mb-4 text-gray-600">A simple landing page. Replace this content with your app UI.</p>

        @if (Route::has('login'))
            <div class="flex gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 border rounded">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 border rounded">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
