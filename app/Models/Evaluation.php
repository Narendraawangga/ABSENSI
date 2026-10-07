<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'intern_id', 'evaluator_id', 'month', 'year',
        'discipline_score', 'attendance_score', 'responsibility_score',
        'communication_score', 'teamwork_score', 'initiative_score',
        'technical_score', 'completion_score', 'comments',
    ];

    public function intern()
    {
        return $this->belongsTo(User::class, 'intern_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
