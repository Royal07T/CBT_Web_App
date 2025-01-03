<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Ensure the student is authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Submit the exam results for a single exam.
     */
    public function submitExam(Request $request, $examId)
    {
        // Ensure $exam is a single instance of the Exam model (using findOrFail to retrieve a single exam)
        $exam = Exam::findOrFail($examId);

        dd($exam);

        // Validate the submitted answers
        $request->validate([
            'answers' => 'required|array', // Ensure the answers are passed as an array
        ]);

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit the exam.');
        }

        // Pass the correct type (Exam instance) to calculateScore
        $score = $this->calculateScore($exam, $request->answers);

        // Save the result (Assuming there is a result relation on the Exam model)
        $exam->results()->create([
            'student_id' => Auth::id(),
            'score' => $score,
        ]);

        // Redirect with a success message
        return redirect()->route('student.dashboard')->with('success', 'Exam submitted successfully.');
    }

    /**
     * Calculate the exam score based on answers.
     */
    private function calculateScore(Exam $exam, array $answers)
    {
        // Fetch the correct answers from the questions associated with the exam
        $correctAnswers = $exam->questions()->pluck('correct_answer', 'id')->toArray();

        $score = 0;

        // Compare the student's answers with the correct answers
        foreach ($answers as $questionId => $answer) {
            if (isset($correctAnswers[$questionId]) && $correctAnswers[$questionId] === $answer) {
                $score++;
            }
        }

        // Return the calculated score
        return $score;
    }

    /**
     * Compile the results for all exams taken by the student.
     */
    public function compileResults()
    {
        // Get all the exams that the student has attempted
        $exams = Exam::with('results')->whereHas('results', function ($query) {
            $query->where('student_id', Auth::id());
        })->get();

        // Initialize the total score
        $totalScore = 0;
        $totalMaxScore = 0;

        // Loop through the exams and calculate the scores
        foreach ($exams as $exam) {
            $score = $exam->results->where('student_id', Auth::id())->first()->score;
            $maxScore = $exam->questions->count();  // assuming each question has equal weight
            $totalScore += $score;
            $totalMaxScore += $maxScore;
        }

        // Return a view to display the student's overall results
        return view('student.results', compact('totalScore', 'totalMaxScore', 'exams'));
    }
}
