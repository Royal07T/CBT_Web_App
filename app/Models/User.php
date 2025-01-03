<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship to Courses (Teacher's courses)
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Relationship to Results (Student's results)
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
