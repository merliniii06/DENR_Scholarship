<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login - {{ config('app.name', 'DENR Scholarship') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin_login.css') }}">
</head>
<body>
    <div class="frame">
        <main class="login-wrap">
            <h1 class="title">ADMIN LOGIN</h1>

            @if ($errors->any())
                <div style="color: red; margin-bottom: 1rem; text-align: center;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/admin_login') }}" method="POST" class="login-form">
                @csrf
                <label class="field">
                    <input type="email" name="login" value="{{ old('login') }}" placeholder="Email" required />
                </label>

                <label class="field">
                    <input type="password" name="password" placeholder="Password" required />
                </label>

                <button type="submit" class="btn">Sign in</button>
            </form>
        </main>
    </div>
</body>
</html>