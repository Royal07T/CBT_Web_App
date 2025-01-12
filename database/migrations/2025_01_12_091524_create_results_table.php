<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Links to the student
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade'); // Links to the exam
            $table->integer('total_score'); // Total score for the exam
            $table->integer('maximum_score'); // Maximum possible score
            $table->string('grade')->nullable(); // Grade assigned (optional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}
