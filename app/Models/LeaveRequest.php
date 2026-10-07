<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id', 'type', 'date_start', 'date_end', 'reason',
        'attachment', 'status', 'admin_notes', 'approved_by',
    ];

    protected $casts = ['date_start' => 'date', 'date_end' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
