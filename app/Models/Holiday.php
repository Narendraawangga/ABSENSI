<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['title', 'date_start', 'date_end', 'description'];

    protected $casts = ['date_start' => 'date', 'date_end' => 'date'];
}
