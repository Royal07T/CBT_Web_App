<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Mass assignment protection
    protected $fillable = ['question', 'type', 'exam_id'];

    // Relationship to Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
