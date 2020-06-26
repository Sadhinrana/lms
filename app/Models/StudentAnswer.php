<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
     protected $table = 'student_answer';

    protected $primaryKey = 'id';

    protected $fillable = ['answer_id','student_id','question_id'];

    public $timestamps = true;

    public function user(){
    	return $this->belongsTo(User::class,'student_id');
    }

    public function answer(){
    	return $this->belongsTo(Answer::class,'answer_id');
    }

    public function question(){
    	return $this->belongsTo(Question::class,'question_id');
    }

    public function attempt(){
      return $this->belongsTo(QuizAttempt::class,'attempt_id');
    }
}
