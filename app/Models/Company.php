<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $primaryKey = 'id';

    protected $fillable = ['name','description','address','company_head_id','password',"country", 'city'];

    public $timestamps = true;


    public function headmaster() {
      return $this->belongsTo(User::class, "company_head_id");
    }

    public function students() {
      return $this->hasMany(User::class, "company_id","id");
    }

    public function user() {
      return $this->hasMany(User::class, "company_id","id");
    }

    public function course() {
      return $this->hasOne(CompanyCourse::class,'company_id','id');
    }

    public function companyCourses() {
      return $this->hasMany(CompanyCourse::class,'company_id','id');
    }
}
