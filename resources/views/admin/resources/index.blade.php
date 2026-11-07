@extends('admin.layout')
@section('title', $definition['label'])
@section('top-title', $definition['label'])
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading">
            <div><h1>{{ $definition['label'] }}</h1><p>Kelola konten {{ strtolower($definition['label']) }} di website.</p></div>
            @unless(($definition['readonly'] ?? false) || ($definition['single'] ?? false))
                <a class="btn" href="{{ route('admin.resources.create', $resource) }}">Tambah {{ $definition['singular'] }}</a>
            @endunless
        </div>
        @if($items->isEmpty())
            <p class="empty">Belum ada data. Tambahkan data pertama untuk ditampilkan di website.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr>@foreach($definition['columns'] as $label)<th>{{ $label }}</th>@endforeach<th>Aksi</th></tr></thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            @foreach($definition['columns'] as $column => $label)
                                @php
                                    $value = data_get($item, $column);
                                    if ($column === 'category_id') $value = $item->category?->name ?? '—';
                                    if ($column === 'active' || $column === 'featured') $value = $value ? 'Ya' : 'Tidak';
                                    if ($column === 'created_at' && $value) $value = $value->format('d M Y H:i');
                                    if (is_string($value) && strlen($value) > 90) $value = \Illuminate\Support\Str::limit($value, 90);
                                    if ($value === null || $value === '') $value = '—';
                                @endphp
                                <td>{{ $value }}</td>
                            @endforeach
                            <td><div class="row-actions">
                                <a href="{{ route('admin.resources.edit', [$resource, $item->getKey()]) }}">{{ ($definition['readonly'] ?? false) ? 'Baca' : 'Edit' }}</a>
                                <form method="post" action="{{ route('admin.resources.destroy', [$resource, $item->getKey()]) }}" data-confirm="Hapus data ini? Tindakan ini tidak dapat dibatalkan.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn ghost small">Hapus</button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $items->links() }}</div>
        @endif
    </section>
@endsection
