<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'course_files';
    
    protected $primaryKey = 'id';

    protected $fillable = ['course_id','file_url'];

    public $timestamps = true;

    public function course(){
    	return $this->belongsTo(Course::class);
    }
}
