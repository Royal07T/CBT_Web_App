<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Ensure only teachers can access teacher routes
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'teacher') {
                return redirect()->route('dashboard')->withErrors(['error' => 'You do not have permission to access this page.']);
            }
            return $next($request);
        });
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

    /**
     * Assign a course to the teacher.
     */
    public function assignCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        // Assign course to teacher
        $teacher = Auth::user();
        $teacher->courses()->attach($request->course_id);

        return redirect()->route('teacher.dashboard')->with('success', 'Course assigned successfully.');
    }

    /**
     * View the teacher's assigned courses.
     */
    public function viewAssignedCourses()
    {
        $courses = Auth::user()->courses; // Get courses assigned to the teacher

        return view('teacher.courses', compact('courses'));
    }
}
