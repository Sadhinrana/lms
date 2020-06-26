<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveLesson extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['week_day_string'];

    /**
     * Get the student group that owns the answer.
     */
    public function studentGroup()
    {
        return $this->belongsTo('App\Models\StudentGroup');
    }

    /**
     * Get the session's week days.
     *
     * @param  string  $value
     * @return string
     */
    public function getWeekDayAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Get the session's week days.
     *
     * @return string
     */
    public function getWeekDayStringAttribute()
    {
        $weekDay = array_map(function ($item) {
            switch ($item) {
                case "0":
                    $item = "Sunday";
                    break;
                case "1":
                    $item = "Monday";
                    break;
                case "2":
                    $item = "Tuesday";
                    break;
                case "3":
                    $item = "Wednesday";
                    break;
                case "4":
                    $item = "Thursday";
                    break;
                case "5":
                    $item = "Friday";
                    break;
                default:
                    $item = "Saturday";
            }

            return $item;
        }, $this->week_day);

        return implode(', ', $weekDay);
    }

    /**
     * Get the students for the session.
     *
     * @return string
     */
    public function students()
    {
        return $this->belongsToMany(User::class);
    }
}
