<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssayAnswer extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the essay that owns the answer.
     */
    public function essay()
    {
        return $this->belongsTo('App\Models\Essay');
    }

    /**
     * Get the user that owns the answer.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the reviews for the essay.
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\EssayReview');
    }
}
