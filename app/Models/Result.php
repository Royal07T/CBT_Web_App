<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    // Mass assignment protection
    protected $fillable = ['exam_id', 'user_id', 'score'];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
