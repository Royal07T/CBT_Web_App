<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'questions' table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade'); // Foreign key to exams
            $table->string('question'); // The question text
            $table->enum('type', ['multiple_choice', 'short_answer', 'true_false']); // Type of question
            $table->string('correct_answer'); // Correct answer (for short answers or MCQs)
            $table->json('options')->nullable(); // Options for multiple-choice questions (stored as JSON)
            $table->timestamps();

            // Index for exam_id for faster querying
            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions'); // Drop the questions table if it exists
    }
};
