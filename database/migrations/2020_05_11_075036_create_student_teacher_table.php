<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_teacher', function (Blueprint $table) {
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('teacher_id');
            $table->string('lesson_mode')->nullable();
            $table->string('lesson_hour')->nullable();
            $table->string('study_mode')->nullable();
            $table->unsignedInteger('student_group_id')->default(99);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_teacher');
    }
}
