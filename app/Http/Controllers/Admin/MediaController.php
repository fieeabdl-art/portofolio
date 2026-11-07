<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\MediaLibrary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', [
            'assets' => MediaAsset::query()->latest()->paginate(24),
            'mediaLibrary' => app(MediaLibrary::class),
        ]);
    }

    public function store(Request $request, MediaLibrary $media): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg,pdf', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:180'],
        ]);
        $media->store($validated['file'], $validated['alt_text'] ?? null);

        return back()->with('success', 'Media berhasil diunggah.');
    }

    public function destroy(MediaAsset $asset, MediaLibrary $media): RedirectResponse
    {
        if (! $media->delete($asset)) {
            return back()->withErrors(['media' => 'Media masih digunakan oleh konten. Ganti atau hapus rujukan konten sebelum menghapusnya.']);
        }

        return back()->with('success', 'Media berhasil dihapus.');
    }
}
