@extends('admin.layout')
@section('title', 'Pesan masuk')
@section('top-title', 'Pesan Masuk')
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading"><div><h1>{{ $item->subject }}</h1><p>Diterima {{ $item->created_at->format('d M Y H:i') }}</p></div><a class="btn ghost small" href="{{ route('admin.resources.index', $resource) }}">Kembali</a></div>
        <dl class="admin-form">
            <div class="field"><dt>Nama</dt><dd>{{ $item->name }}</dd></div>
            <div class="field"><dt>Email</dt><dd><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></dd></div>
            @if($item->phone)<div class="field"><dt>Telepon</dt><dd>{{ $item->phone }}</dd></div>@endif
            <div class="field full"><dt>Pesan</dt><dd style="white-space:pre-wrap">{{ $item->message }}</dd></div>
        </dl>
        <div class="form-actions">
            <a class="btn" href="mailto:{{ $item->email }}?subject={{ rawurlencode('Re: '.$item->subject) }}">Balas via email</a>
            <form method="post" action="{{ route('admin.resources.destroy', [$resource, $item->id]) }}" data-confirm="Hapus pesan ini?">
                @csrf @method('DELETE')<button class="btn ghost" type="submit">Hapus pesan</button>
            </form>
        </div>
    </section>
@endsection
