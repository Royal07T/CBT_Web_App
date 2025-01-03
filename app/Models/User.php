<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'email', 'password', 'role'];

    // Relationship with Result model
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Get the user's full name (first, middle, last)
    public function getFullNameAttribute()
    {
        // Combine first, middle, and last names
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name;
    }

    // Scope for students
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    // Scope for teachers
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    // Check if user is a student
    public function isStudent()
    {
        return $this->role === 'student';
    }

    // Check if user is a teacher
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }
}
