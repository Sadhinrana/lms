<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = "quiz_attempt";

    public function quiz() {
        return $this->belongsTo(Quizz::class,'quiz_id','id');
    }

    public function studentAnswers() {
        return $this->hasMany(studentAnswer::class, 'attempt_id', 'id');
    }
}
