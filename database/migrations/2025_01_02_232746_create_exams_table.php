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
        // Create the 'exams' table
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Exam name
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Foreign key to course
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Foreign key to teacher (creator of the exam)
            $table->timestamps();

            // Index for course_id and teacher_id for faster querying
            $table->index('course_id');
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams'); // Drop the exams table if it exists
    }
};
