<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Mass assignment protection
    protected $fillable = ['question', 'type', 'exam_id', 'correct_answer', 'options'];

    // Relationship to Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the options for the question (assuming it's a multiple-choice question).
     */
    public function getOptionsAttribute($value)
    {
        return json_decode($value, true); // Decode JSON options into an array
    }

    /**
     * Set the options for the question (convert to JSON before saving).
     */
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value); // Encode options into JSON before saving
    }

    /**
     * Check if the student's answer is correct.
     */
    public function isAnswerCorrect($answer)
    {
        return $this->correct_answer === $answer; // Compare with the correct answer
    }

    /**
     * Scope to filter questions by exam.
     */
    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId); // Filter by exam ID
    }
}
