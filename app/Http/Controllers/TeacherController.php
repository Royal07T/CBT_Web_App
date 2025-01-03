<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeacherController extends Controller
{
    // Ensure the teacher is authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a new exam for a course.
     */
    public function createExam(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Check if the user is authenticated
        if (!auth()->guard('web')->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an exam.');
        }

        // Get the authenticated teacher's ID
        $teacherId = auth()->guard('web')->id(); // Use guard('web') for explicit session-based authentication

        // Create the exam and associate it with the authenticated teacher
        $exam = Exam::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
            'teacher_id' => $teacherId,  // Associate exam with teacher
        ]);

        // Redirect with a success message
        return redirect()->route('teacher.viewExams')->with('success', 'Exam created successfully.');
    }

    /**
     * View exams created by the teacher.
     */
    public function viewExams()
    {
        // Check if the user is authenticated
        if (!auth()->guard('web')->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your exams.');
        }

        $teacherId = auth()->guard('web')->id(); // Fetch authenticated teacher ID

        // Get the exams created by the teacher
        $exams = Exam::where('teacher_id', $teacherId)->get();

        return view('teacher.exams', compact('exams'));
    }

    /**
     * Grade the exam (This is just a placeholder for grading logic)
     */
    public function gradeExam($examId)
    {
        // Check if the user is authenticated
        if (!auth()->guard('web')->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to grade exams.');
        }

        // Fetch the exam using the examId
        $exam = Exam::findOrFail($examId);

        // Check if the current teacher is the one who created the exam
        if ($exam->teacher_id !== auth()->guard('web')->id()) {
            return redirect()->route('teacher.viewExams')->with('error', 'You cannot grade this exam.');
        }

        // Grading logic can be added here

        return view('teacher.gradeExam', compact('exam'));
    }
}
