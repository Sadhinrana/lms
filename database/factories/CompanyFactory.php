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

$factory->define(App\Models\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'description'	=> $faker->text,
        'address' => $faker->address,
        'company_head_id' => factory('App\Models\User')->create()->id
    ];
});
