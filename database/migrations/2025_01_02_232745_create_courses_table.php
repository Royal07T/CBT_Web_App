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
        // Create the 'courses' table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Course name
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Foreign key to teacher (user)
            $table->timestamps();

            // Index for teacher_id for faster querying
            $table->index('teacher_id');
        });

        // Create the pivot table for the many-to-many relationship (students)
        Schema::create('course_user', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['teacher', 'student'])->default('student'); // Role of the user in the course (student or teacher)
            $table->timestamps();

            $table->primary(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user'); // Drop the pivot table
        Schema::dropIfExists('courses'); // Drop the courses table
    }
};
