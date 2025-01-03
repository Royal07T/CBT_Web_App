<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    // Mass assignment protection
    protected $fillable = ['name', 'course_id'];

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
}
