<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CMS') · {{ config('app.name', 'Portofolio') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="glass admin-sidebar">
        <a class="admin-brand chrome" href="{{ route('admin.dashboard') }}">CMS.<small>PORTOFOLIO GLASS SILVER</small></a>
        <nav class="admin-menu" aria-label="Menu CMS">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            @foreach([
                'profile' => 'Profil Saya',
                'statistics' => 'Statistik',
                'educations' => 'Pendidikan',
                'experiences' => 'Pengalaman',
                'achievements' => 'Prestasi',
                'skills' => 'Keahlian',
                'categories' => 'Kategori Proyek',
                'projects' => 'Proyek',
            ] as $resource => $label)
                <a class="{{ request()->is('admin/'.$resource.'*') ? 'active' : '' }}" href="{{ route('admin.resources.index', $resource) }}">{{ $label }}</a>
            @endforeach
            <a class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">Galeri Media</a>
            <a class="{{ request()->is('admin/processes*') ? 'active' : '' }}" href="{{ route('admin.resources.index', 'processes') }}">Proses Kerja</a>
            <a class="{{ request()->is('admin/messages*') ? 'active' : '' }}" href="{{ route('admin.resources.index', 'messages') }}">Pesan Masuk</a>
            <a class="{{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.resources.index', 'settings') }}">Pengaturan Website</a>
            <a class="{{ request()->routeIs('admin.account.*') ? 'active' : '' }}" href="{{ route('admin.account.edit') }}">Pengaturan Akun</a>
        </nav>
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn ghost small">Keluar</button>
        </form>
    </aside>
    <div class="admin-content">
        <header class="glass admin-top">
            <strong>@yield('top-title', 'Panel Admin')</strong>
            <a class="btn ghost small" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat website ↗</a>
        </header>
        @if(session('success'))<div class="admin-alert" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="admin-alert error" role="alert">
                <strong>Periksa kembali data:</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
