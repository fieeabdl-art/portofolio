<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'summary', 'description', 'technologies',
        'project_date', 'demo_url', 'repository_url', 'thumbnail_path', 'sort_order', 'featured',
    ];

    protected function casts(): array
    {
        return ['project_date' => 'date', 'featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
