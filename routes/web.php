<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


// Default route for the welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Student Routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/exam/{examId}', [StudentController::class, 'takeExam'])->name('takeExam');
        Route::post('/exam/{examId}/submit', [StudentController::class, 'submitExam'])->name('submitExam');
    });

    // Teacher Routes
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/exams', [TeacherController::class, 'viewExams'])->name('viewExams');
        Route::get('/exam/create', [TeacherController::class, 'createExam'])->name('createExam');
        Route::post('/exam/store', [TeacherController::class, 'storeExam'])->name('storeExam');
        Route::get('/exam/{examId}/grade', [TeacherController::class, 'gradeExam'])->name('gradeExam');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [AdminController::class, 'manageCourses'])->name('manageCourses');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('manageUsers');
    });
});
