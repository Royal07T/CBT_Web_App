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
}
