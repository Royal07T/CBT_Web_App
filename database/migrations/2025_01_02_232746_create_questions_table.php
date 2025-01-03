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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('type', ['objective', 'theory']);
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('correct_answer')->nullable(); // Add this line for the correct_answer column
            $table->timestamps();

            // Index for the exam_id for faster querying
            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
