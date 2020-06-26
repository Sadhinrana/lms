<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    protected $table = 'student_quiz';

    protected $primaryKey = 'id';

    protected $fillable = ['student_id','quiz_id','time_taken','start_time'];

    public $timestamps = true;

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function quiz(){
    	return $this->belongsTo(Quizz::class);
    }

}
