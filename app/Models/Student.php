<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
