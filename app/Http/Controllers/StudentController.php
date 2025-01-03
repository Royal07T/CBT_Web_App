<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class StudentController extends Controller
{
    // Ensure the student is authenticated
    public function __construct()
    {
        // Use the middleware method for authentication
        $this->middleware('auth');
    }

    /**
     * Show the student dashboard.
     */
    public function dashboard()
    {
        // Fetch the exams that the student can take (assuming this is how you want to display them)
        $exams = Exam::all(); // You can modify this to fetch exams based on course, or other criteria

        return view('student.dashboard', compact('exams'));
    }

    /**
     * Take an exam.
     */
    public function takeExam($examId)
    {
        // Fetch the exam by its ID
        $exam = Exam::findOrFail($examId);

        // Check if the student is eligible to take the exam (e.g., if it's not closed or restricted)
        // You can add more conditions based on your application's requirements
        // Example: if ($exam->is_active !== true) { ... }

        return view('student.takeExam', compact('exam'));
    }

    /**
     * Submit the exam results.
     */
    public function submitExam(Request $request, $examId)
    {
        // Fetch the exam by its ID
        $exam = Exam::findOrFail($examId);

        // Validate the submitted answers (you can extend this with more validation rules)
        $request->validate([
            'answers' => 'required|array', // Assuming the answers are passed as an array
        ]);

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit the exam.');
        }

        // Assuming we process the results and save them in a Result model
        $result = $exam->results()->create([
            'student_id' => Auth::user()->id, // Use Auth::user()->id to get the authenticated user's ID
            'score' => 0,  // You can calculate the score here
        ]);

        // Redirect with a success message
        return redirect()->route('student.dashboard')->with('success', 'Exam submitted successfully.');
    }
}
