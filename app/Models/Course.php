<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Mass assignment protection
    protected $fillable = ['name', 'teacher_id'];

    // Relationship to User (Teacher)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relationship to Exam
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    // Relationship to Users (Students) - Many to Many relationship
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->wherePivot('role', 'student');
    }

    /**
     * Check if a student is enrolled in the course.
     */
    public function isStudentEnrolled($userId)
    {
        return $this->students()->where('user_id', $userId)->exists();
    }

    /**
     * Enroll a student in the course.
     */
    public function enrollStudent(User $user)
    {
        // Only enroll if the user is not already enrolled
        if (!$this->isStudentEnrolled($user->id)) {
            $this->students()->attach($user->id, ['role' => 'student']);
        }
    }

    /**
     * Unenroll a student from the course.
     */
    public function unenrollStudent(User $user)
    {
        $this->students()->detach($user->id);
    }
}
