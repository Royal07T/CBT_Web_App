<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Answer extends Model
{
    // Mass assignment protection
    protected $fillable = ['user_id', 'exam_id', 'question_id', 'answer'];

    // Relationship to User (Student who answered)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Exam (The exam the answer belongs to)
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Relationship to Question (The question the answer is associated with)
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Check if the answer is correct by comparing with the correct answer in the Question model.
     */
    public function isCorrect()
    {
        return $this->answer == $this->question->correct_answer;
    }
}
