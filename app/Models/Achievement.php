<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'organization',
        'description',
        'achieved_at',
        'image_path',
        'sort_order',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'achieved_at' => 'date',
            'featured' => 'boolean',
        ];
    }
}
