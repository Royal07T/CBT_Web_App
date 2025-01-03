<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Result extends Model
{
    // Mass assignment protection
    protected $fillable = ['exam_id', 'user_id', 'score'];

    // Relationship to User (The student who took the exam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Exam (The exam that was taken)
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the user's score for the exam.
     * Here you can format or manipulate the score if needed.
     */
    public function getScoreAttribute($value)
    {
        return round($value, 2); // Format score to 2 decimal places
    }

    /**
     * Scope to get results by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get results by exam.
     */
    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    /**
     * Calculate the total score based on the user's answers.
     * This method calculates the score by checking correct answers.
     */
    public static function calculateTotalScore($examId, $userId)
    {
        $userAnswers = Answer::where('exam_id', $examId)
            ->where('user_id', $userId)
            ->get();

        $score = 0;

        foreach ($userAnswers as $answer) {
            // Check if the answer is correct by comparing it to the question's correct answer
            if ($answer->isCorrect()) {
                $score++;
            }
        }

        return $score;
    }

    /**
     * Create or update the result for the given exam and user.
     * This method will calculate and store the score.
     */
    public static function createOrUpdateResult($examId, $userId)
    {
        $score = self::calculateTotalScore($examId, $userId);

        // Check if a result already exists for this exam and user
        $result = self::firstOrNew([
            'exam_id' => $examId,
            'user_id' => $userId,
        ]);

        // Update or create the result with the calculated score
        $result->score = $score;
        $result->save();

        return $result;
    }

    /**
     * Check if the result is passing (can be based on score or other criteria).
     */
    public function isPassing($passingScore = 50)
    {
        return $this->score >= $passingScore;
    }
}
