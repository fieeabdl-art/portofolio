@extends('admin.layout')
@section('title', ($item ? 'Edit ' : 'Tambah ').$definition['singular'])
@section('top-title', $definition['label'])
@section('content')
    <section class="admin-panel glass">
        <div class="admin-heading">
            <div><h1>{{ $item ? 'Edit '.$definition['singular'] : 'Tambah '.$definition['singular'] }}</h1><p>Data akan langsung digunakan oleh website publik.</p></div>
            <a class="btn ghost small" href="{{ route('admin.resources.index', $resource) }}">Kembali</a>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ $item ? route('admin.resources.update', [$resource, $item->getKey()]) : route('admin.resources.store', $resource) }}">
            @csrf
            @if($item) @method('PUT') @endif
            <div class="admin-form">
                @foreach($definition['fields'] as $name => $field)
                    @php
                        $value = old($name, $item ? data_get($item, $name) : '');
                        $full = in_array($field['type'], ['textarea', 'gallery'], true) || in_array($name, ['biography', 'description']);
                        $id = 'field-'.$name;
                    @endphp
                    @if($field['type'] === 'gallery')
                        <div class="field full">
                            <label for="{{ $id }}">{{ $field['label'] }} <small>(maksimal 12 gambar, 5 MB per file)</small></label>
                            <input id="{{ $id }}" type="file" name="project_gallery[]" accept="image/jpeg,image/png,image/webp" multiple>
                            @if($item && $item->images->isNotEmpty())
                                <div class="media-grid" style="margin-top:12px">
                                    @foreach($item->images as $image)
                                        <label class="glass media-card">
                                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->alt ?? $item->title }}">
                                            <small><input type="checkbox" name="remove_gallery[]" value="{{ $image->id }}"> Tandai untuk dihapus dari proyek</small>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @continue
                    @endif
                    <div class="field {{ $full ? 'full' : '' }}">
                        <label for="{{ $id }}">{{ $field['label'] }}</label>
                        @if($field['type'] === 'textarea')
                            <textarea id="{{ $id }}" name="{{ $name }}">{{ $value }}</textarea>
                        @elseif($field['type'] === 'select')
                            @php($fieldOptions = is_array($field['options']) ? $field['options'] : ($options[$field['options']] ?? []))
                            <select id="{{ $id }}" name="{{ $name }}">
                                @if(str_starts_with($field['rules'], 'nullable'))<option value="">— Tidak ada —</option>@endif
                                @foreach($fieldOptions as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif($field['type'] === 'checkbox')
                            <label><input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" @checked((bool) $value)> Aktif</label>
                        @elseif(in_array($field['type'], ['image', 'pdf'], true))
                            @if($value)
                                <div id="preview-{{ $name }}">
                                    @if($field['type'] === 'image')
                                        <img src="{{ asset('storage/'.$value) }}" alt="Media saat ini" style="max-width:180px;max-height:140px;border-radius:10px;object-fit:cover">
                                    @else
                                        <a href="{{ asset('storage/'.$value) }}" target="_blank" rel="noopener">Lihat PDF saat ini</a>
                                    @endif
                                </div>
                            @else
                                <div id="preview-{{ $name }}"></div>
                            @endif
                            <input id="{{ $id }}" type="file" name="{{ $name }}" accept="{{ $field['type'] === 'pdf' ? 'application/pdf' : 'image/jpeg,image/png,image/webp' }}" data-preview-input="#preview-{{ $name }}">
                            <small>{{ $value ? 'Kosongkan jika tidak ingin mengganti file.' : 'Pilih file untuk diunggah.' }}</small>
                        @else
                            <input id="{{ $id }}" type="{{ $field['type'] }}" name="{{ $name }}" value="{{ $value }}" @if($field['type'] === 'number') min="0" @endif>
                        @endif
                        @error($name)<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                @endforeach
            </div>
            <div class="form-actions">
                <button class="btn" type="submit">Simpan perubahan</button>
                <a class="btn ghost" href="{{ route('admin.resources.index', $resource) }}">Batal</a>
            </div>
        </form>
    </section>
@endsection
