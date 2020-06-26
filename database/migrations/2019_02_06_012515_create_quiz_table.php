<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description',1000)->nullable();
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('course')->onDelete('cascade');
            $table->string('quiz_image')->nullable();
            $table->string('duration')->nullable();
            $table->integer('grade_to_pass')->nullable();
            $table->string('retake_after')->nullable();            
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('previous_quiz')->unsigned()->nullable();
            $table->foreign('previous_quiz')->references('id')->on('quiz');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz');
    }
}
