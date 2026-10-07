<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternProfile extends Model
{
    protected $fillable = [
        'user_id', 'nim', 'university', 'major', 'phone',
        'division', 'supervisor_id', 'start_date', 'end_date', 'avatar',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
