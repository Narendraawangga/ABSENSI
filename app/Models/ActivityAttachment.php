<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAttachment extends Model
{
    protected $fillable = ['activity_id', 'file_path', 'file_name', 'file_type'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
