<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','phone_number', 'email', 'password', 'avatar_url', 'role','company_id', 'instructor_id', 'is_deleted', 'lesson_mode', 'lesson_hour', 'study_mode', 'student_group_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    protected $hidden = ['password','created_at','updated_at'];

    public function company(){
      return $this->hasOne(Company::class, "company_head_id");
    }

    public function headmaster(){
        return $this->hasmany(Company::class);
    }

    public function lesson_student(){
        return $this->hasmany(Student::class);
    }

    public function companyLearning() {
        return $this->belongsTo(Company::class, "company_id",'id');
    }

    public function course() {
        return $this->hasMany(Course::class,"instructor_id","id");
    }

    public function studentCourse() {
        return $this->hasMany(StudentCourse::class, "student_id", "id");
    }

    public function studentQuizBlock() {
        return $this->hasOne(StudentQuizBlock::class, "user_id", "id");
    }

    public function attendances() {
        return $this->hasMany('App\Attendance');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }


    public function students()
    {
        return $this->belongsToMany(User::class, 'student_teacher', 'teacher_id', 'student_id');
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'student_teacher', 'student_id', 'teacher_id')->withPivot('lesson_mode', 'lesson_hour', 'study_mode', 'student_group_id');
    }

    /**
     * Get the answers for the essay.
     */
    public function answers()
    {
        return $this->hasMany('App\Models\EssayAnswer');
    }

    /**
     * Get the liveLesson for the user.
     */
    public function liveLesson()
    {
        return $this->hasMany('App\Models\LiveLesson');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the answers for the user.
     */
    public function liveSessions()
    {
        return $this->belongsToMany(LiveLesson::class);
    }
}
