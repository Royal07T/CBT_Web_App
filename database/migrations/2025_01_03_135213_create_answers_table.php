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
        // Create the 'answers' table
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users (students)
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade'); // Foreign key to exams
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // Foreign key to questions
            $table->text('answer'); // Store the student's answer
            $table->timestamps();

            // Index for efficient querying
            $table->index(['user_id', 'exam_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers'); // Drop the answers table
    }
};
