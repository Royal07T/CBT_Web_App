<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a new exam for a course.
     */
    public function createExam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Create the exam
        Exam::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
            'teacher_id' => Auth::id(),
        ]);

        return redirect()->route('teacher.viewExams')->with('success', 'Exam created successfully.');
    }

    /**
     * View exams created by the teacher.
     */
    public function viewExams()
    {
        $exams = Exam::where('teacher_id', Auth::id())->paginate(10);

        return view('teacher.exams', compact('exams'));
    }

    /**
     * Grade the exam.
     */
    public function gradeExam($examId)
    {
        $exam = Exam::where('id', $examId)->where('teacher_id', Auth::id())->firstOrFail();

        return view('teacher.gradeExam', compact('exam'));
    }
}
