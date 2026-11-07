@extends('admin.layout')
@section('title', 'Dashboard')
@section('top-title', 'Dashboard')
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading">
            <div><h1>Ringkasan</h1><p>Ikhtisar konten dan pesan masuk.</p></div>
            <a class="btn" href="{{ route('admin.resources.index', 'profile') }}">Perbarui profil</a>
        </div>
        <div class="admin-cards">
            @foreach([
                'projects' => 'Proyek',
                'experiences' => 'Pengalaman',
                'skills' => 'Keahlian',
                'education' => 'Pendidikan',
                'messages' => 'Pesan masuk',
                'unread' => 'Pesan belum dibaca',
                'media' => 'Media tersimpan',
            ] as $key => $label)
                <div class="glass metric"><b>{{ $counts[$key] }}</b><span>{{ $label }}</span></div>
            @endforeach
        </div>
    </section>
    <section class="admin-panel glass">
        <div class="admin-heading"><div><h1>Pesan terbaru</h1><p>Pesan dari formulir kontak publik.</p></div><a href="{{ route('admin.resources.index', 'messages') }}">Lihat semua</a></div>
        @if($recentMessages->isEmpty())
            <p class="empty">Belum ada pesan yang masuk.</p>
        @else
            <div class="admin-table-wrap"><table class="admin-table">
                <thead><tr><th>Nama</th><th>Subjek</th><th>Diterima</th><th></th></tr></thead>
                <tbody>
                @foreach($recentMessages as $message)
                    <tr>
                        <td>{{ $message->name }}</td><td>{{ $message->subject }}</td><td>{{ $message->created_at->format('d M Y H:i') }}</td>
                        <td><a href="{{ route('admin.resources.edit', ['messages', $message->id]) }}">Baca pesan</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
        @endif
    </section>
@endsection
