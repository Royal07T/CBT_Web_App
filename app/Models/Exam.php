<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    // Mass assignment protection
    protected $fillable = ['name', 'course_id', 'teacher_id'];

    // Relationship to Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship to Questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relationship to Results (students' results for this exam)
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Relationship to Teacher (creator of the exam)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Check if a student has already taken the exam.
     */
    public function hasStudentTakenExam($studentId)
    {
        return $this->results()->where('student_id', $studentId)->exists();
    }

    /**
     * Get the total score for this exam.
     * Assuming each question has a fixed score of 1, adjust as needed.
     */
    public function getTotalScore()
    {
        return $this->questions->count();  // Assuming each question is worth 1 point.
    }

    /**
     * Enroll a student in the exam (assuming it's a manual enrollment system).
     */
    public function enrollStudent(User $student)
    {
        if (!$this->hasStudentTakenExam($student->id)) {
            $this->results()->create([
                'user_id' => $student->id, // Enroll the student
                'score' => 0,  // Initially setting score to 0, adjust based on the exam logic
            ]);
        }
    }
}
