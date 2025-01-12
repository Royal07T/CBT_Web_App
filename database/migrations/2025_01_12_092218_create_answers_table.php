<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Links to the students table
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // Links to the questions table
            $table->text('answer')->nullable(); // The actual answer (text or option choice)
            $table->boolean('is_correct')->nullable(); // For objective questions
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
