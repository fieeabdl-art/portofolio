<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Experience;
use App\Models\MediaAsset;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\WorkProcess;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaLibrary
{
    public function store(UploadedFile $file, ?string $alt = null): string
    {
        $path = $file->store('portfolio/'.now()->format('Y/m'), 'public');

        MediaAsset::create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'size' => $file->getSize(),
            'alt_text' => $alt,
        ]);

        return $path;
    }

    public function isInUse(string $path): bool
    {
        return Profile::query()->where('photo_path', $path)->orWhere('about_photo_path', $path)->orWhere('cv_path', $path)->exists()
            || Skill::query()->where('icon_path', $path)->exists()
            || WorkProcess::query()->where('icon_path', $path)->exists()
            || Project::query()->where('thumbnail_path', $path)->exists()
            || ProjectImage::query()->where('path', $path)->exists()
            || Experience::query()->where('image_path', $path)->exists()
            || Achievement::query()->where('image_path', $path)->exists();
    }

    public function delete(MediaAsset $asset): bool
    {
        if ($this->isInUse($asset->path)) {
            return false;
        }

        $disk = Storage::disk($asset->disk);
        if ($disk->exists($asset->path) && ! $disk->delete($asset->path)) {
            throw new RuntimeException('Media file could not be removed from storage.');
        }
        $asset->delete();

        return true;
    }
}
