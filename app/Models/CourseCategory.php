<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'id';

    protected $fillable = ['name','description'];

    public $timestamps = true;

    public function course() {
    	return $this->hasmany(Course::class);
    }

    public function lesson() {
    	return $this->belongsToMany(Lesson::class);
    }
}
