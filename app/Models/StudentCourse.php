<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{

    protected $primaryKey = 'id';

    protected $fillable = ['student_id','course_id'];

    public $timestamps = true;

    public function student_of_course(){
    	return $this->belongsToMany(Course::class);
    }

    public function course(){
        return $this->hasOne(Course::class,'id','course_id');
    }

    public function student() {
        return $this->hasOne(User::class,'id','student_id');
    }

}
