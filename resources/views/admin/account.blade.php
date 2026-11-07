@extends('admin.layout')
@section('title', 'Pengaturan Akun')
@section('top-title', 'Pengaturan Akun')
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading"><div><h1>Pengaturan Akun</h1><p>Ubah identitas masuk atau ganti kata sandi admin.</p></div></div>
        <form method="post" action="{{ route('admin.account.update') }}">
            @csrf @method('PUT')
            <div class="admin-form">
                <div class="field"><label for="name">Nama admin</label><input id="name" name="name" value="{{ old('name', $account->name) }}" required></div>
                <div class="field"><label for="email">Email admin</label><input id="email" type="email" name="email" value="{{ old('email', $account->email) }}" required></div>
                <div class="field"><label for="current_password">Kata sandi saat ini (wajib saat mengganti password)</label><input id="current_password" type="password" name="current_password" autocomplete="current-password"></div>
                <div class="field"><label for="password">Kata sandi baru (minimal 12 karakter)</label><input id="password" type="password" name="password" autocomplete="new-password"></div>
                <div class="field"><label for="password_confirmation">Konfirmasi kata sandi baru</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"></div>
            </div>
            <div class="form-actions"><button class="btn" type="submit">Simpan akun</button></div>
        </form>
    </section>
@endsection
