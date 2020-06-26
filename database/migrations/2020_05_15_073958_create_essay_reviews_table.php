<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssayReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essay_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->text('review');
            $table->boolean('is_student');
            $table->timestamp('seen_at')->nullable();
            $table->unsignedInteger('essay_answer_id');
            $table->foreign('essay_answer_id')->references('id')->on('essay_answers')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('essay_reviews');
    }
}
