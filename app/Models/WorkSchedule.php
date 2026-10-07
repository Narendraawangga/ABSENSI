<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = ['name', 'time_in', 'time_out', 'late_tolerance', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];
}
