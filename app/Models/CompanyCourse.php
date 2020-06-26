<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCourse extends Model
{
  protected $table = 'company_course';

  protected $primaryKey = 'id';

  protected $fillable = ['company_id','course_id'];

  public $timestamps = true;

  public function company(){
    return $this->belongsTo(Company::class);
  }
  
  public function course(){
    return $this->belongsTo(Course::class,'course_id','id');
  }
}
