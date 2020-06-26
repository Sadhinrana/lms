<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answer';

    protected $primaryKey = 'id';

    protected $fillable = ['question_id','title','is_correct','child_index'];
    //protected $hidden = ['is_correct'];

    public $timestamps = true;

    public function question(){
    	return $this->belongsTo(Question::class,'question_id');
    }

    public function quizz(){
    	return $this->belongsTo(Quizz::class);
    }

    public function student_answer(){
        return $this->hasmany(Student_answer::class);
    }
}
