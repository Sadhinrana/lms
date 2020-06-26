<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the course that owns the essay.
     */
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    /**
     * Get the answers for the essay.
     */
    public function answers()
    {
        return $this->hasMany('App\Models\EssayAnswer');
    }
}
