<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['company', 'position', 'description', 'start_date', 'end_date', 'current', 'sort_order', 'image_path'];

    protected function casts(): array
    {
        return ['current' => 'boolean'];
    }
}
