<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade'); // Links to the exam
            $table->text('question_text'); // The question content
            $table->enum('question_type', ['objective', 'theory']); // Type of question
            $table->json('options')->nullable(); // Stores options for objective questions
            $table->text('correct_answer')->nullable(); // Correct answer for objective or model answer for theory
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
