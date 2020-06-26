<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
    protected $table = 'student_lesson';
    
    protected $primaryKey = 'id';

    protected $fillable = ['student_id','lesson_id','is_learned','time_learned'];

    public $timestamps = true;

    public function student_of_lesson(){
    	return $this->hasOne(StudentCourse::class);
    }
}
