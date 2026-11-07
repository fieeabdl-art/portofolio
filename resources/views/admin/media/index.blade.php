@extends('admin.layout')
@section('title', 'Galeri Media')
@section('top-title', 'Galeri Media')
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading"><div><h1>Galeri Media</h1><p>File yang terpakai dilindungi agar tidak terhapus dari konten.</p></div></div>
        <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            <div class="field"><label for="media-file">Unggah gambar atau PDF (maksimal 10 MB)</label><input id="media-file" type="file" name="file" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf" required data-preview-input="#media-preview"></div>
            <div class="field"><label for="alt-text">Teks alternatif / keterangan</label><input id="alt-text" name="alt_text" maxlength="180"></div>
            <div id="media-preview" class="field full"></div>
            <div class="field full"><button class="btn" type="submit">Unggah media</button></div>
        </form>
    </section>
    <section class="admin-panel glass">
        @if($assets->isEmpty())
            <p class="empty">Belum ada media yang diunggah.</p>
        @else
            <div class="media-grid">
                @foreach($assets as $asset)
                    <article class="glass media-card">
                        @if(str_starts_with($asset->mime_type, 'image/'))
                            <img src="{{ asset('storage/'.$asset->path) }}" alt="{{ $asset->alt_text ?? $asset->original_name }}">
                        @else
                            <a class="media-file" href="{{ asset('storage/'.$asset->path) }}" target="_blank" rel="noopener">Buka PDF</a>
                        @endif
                        <small>{{ $asset->original_name }}<br>{{ number_format($asset->size / 1024, 1) }} KB</small>
                        @if($mediaLibrary->isInUse($asset->path))
                            <small>Digunakan konten · penghapusan dikunci</small>
                        @else
                            <form method="post" action="{{ route('admin.media.destroy', $asset) }}" data-confirm="Hapus file media ini secara permanen?">
                                @csrf @method('DELETE')<button class="btn ghost small" type="submit">Hapus media</button>
                            </form>
                        @endif
                    </article>
                @endforeach
            </div>
            <div class="pagination">{{ $assets->links() }}</div>
        @endif
    </section>
@endsection
