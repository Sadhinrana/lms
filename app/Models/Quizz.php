<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizz extends Model
{
    protected $table = 'quiz';

    protected $primaryKey = 'id';

    protected $fillable = ['title','description','lesson_id','quiz_image','duration','grade_to_pass','retake_after','previous_quiz'];

    public $timestamps = true;

    public function question() {
    	return $this->hasMany(Question::class,'quiz_id','id');
    }

    public function studentQuiz(){
    	return $this->hasMany(StudentQuiz::class,'quiz_id','id');
    }

    public function studentAnswer(){
    	return $this->hasMany(StudentAnswer::class,'quiz_id','id');
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class,'quiz_id','id');
    }

    public function course() {
        return $this->belongsTo(Course::class,'course_id', 'id');
    } 

}
