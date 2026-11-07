<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<main class="glass auth-card">
    <a class="logo chrome" href="{{ route('home') }}">PORTOFOLIO.</a>
    <h1>Masuk CMS</h1>
    <p style="color:var(--mut)">Kelola konten portofolio Anda.</p>
    @if($errors->any())<div class="admin-alert error">{{ $errors->first() }}</div>@endif
    <form method="post" action="{{ route('admin.login.store') }}" class="admin-form" style="grid-template-columns:1fr">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
        </div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>
        </div>
        <label style="color:var(--mut);font-size:13px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button class="btn" type="submit">Masuk</button>
    </form>
</main>
</body>
</html>
