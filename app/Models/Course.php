<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';

    protected $primaryKey = 'id';

    protected $fillable = ['title','description','video_link','category_id','duration','instructor_id','is_published','grade_to_pass','course_level'];

    public $timestamps = true;

    public function category() {
    	return $this->belongsTo(CourseCategory::class);
    }

    public function companyCourse() {
        return $this->hasOne(CompanyCourse::class,'course_id','id');
    }

    public function course_files() {
    	return $this->hasmany(File::class);
    }

    public function quiz(){
      return $this->hasmany(Quizz::class)->where('deleted',0);
    }

    public function lesson() {
      return $this->hasmany(Lesson::class);
    }

    public function studentCourse() {
        return $this->belongsTo(StudentCourse::class,'id','course_id');
    }

    public function instructors() {
        return $this->belongsTo(Users::class, "instructor_id", "id");
    }

    /**
     * Get the essays for the course.
     */
    public function essays()
    {
        return $this->hasMany('App\Models\Essay');
    }
}
