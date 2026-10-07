<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'date', 'title', 'description', 'start_time',
        'end_time', 'category', 'progress', 'constraints', 'learnings', 'admin_comment',
    ];

    protected $casts = ['date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(ActivityAttachment::class);
    }
}
