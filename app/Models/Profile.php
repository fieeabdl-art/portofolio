<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name', 'brand', 'profession', 'tagline', 'short_bio', 'biography',
        'email', 'phone', 'whatsapp', 'location', 'instagram_url', 'linkedin_url',
        'github_url', 'photo_path', 'about_photo_path', 'cv_path',
    ];
}
