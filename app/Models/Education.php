<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = ['institution', 'degree', 'description', 'start_date', 'end_date', 'sort_order'];
}
