<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'date', 'priority', 'attachment', 'created_by'];

    protected $casts = ['date' => 'date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
