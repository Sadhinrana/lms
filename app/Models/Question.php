<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $primaryKey = 'id';
    protected $fillable = ['sort_id','quiz_id','title','formula','description','question_image','audio_file','video_link','type','score','parent_id'];
    public $timestamps = true;

    public function child_questions(){
        return $this->hasMany(Question::class, "parent_id")->orderBy('sort_id');
    }

    public function quiz() {
    	return $this->belongsTo(Quizz::class,"quiz_id");
    }

    public function answers() {
    	return $this->hasMany(Answer::class,'question_id', 'id');
    }

    public function studentAnswer() {
        return $this->hasmany(StudentAnswer::class);
    }


    public static function boot() {
        parent::boot();

        static::deleting(function($user) {
             $user->child_questions()->delete();             
        });
    }
}
