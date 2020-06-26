<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lesson';

    protected $primaryKey = 'id';

    protected $fillable = ['title','description','video_link','course_id','orders'];

    public $timestamps = true;

    public function course() {
    	return $this->belongsTo(Course::class);
    }

    public function quiz() {
    	return $this->hasmany(Quizz::class);
    }
}
