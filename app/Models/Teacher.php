<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'specialization', // For example, the subjects they teach
    ];

    protected $hidden = ['password'];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
