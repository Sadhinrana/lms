<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Course::class, function (Faker $faker) {
    return [
        'title' 		=> $faker->jobTitle,
        'description'	=> $faker->text,
        'video_link'	=> 'https://www.youtube.com/embed/S2bZ7AcBgiM',
        'category_id'	=> factory('App\Models\CourseCategory')->create()->id,
        'duration'		=> $faker->time($format = 'H:i'),
        'instructor_id' => factory('App\Models\User')->create()->id,
        'is_published'	=> $faker->randomElement($array = array (0,1))
    ];
});
