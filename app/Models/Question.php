<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array', // Automatically handle JSON encoding/decoding
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
