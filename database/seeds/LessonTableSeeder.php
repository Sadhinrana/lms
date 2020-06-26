<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class LessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = \Faker\Factory::create();
      $courses = DB::table("course")->pluck('id');

      for($i = 0; $i < 60; $i++){
        DB::table('lesson')->insert([
            'title' => $faker->catchPhrase,
            'description' => $faker->text,
            'video_link'	=> 'https://www.youtube.com/embed/S2bZ7AcBgiM',
            'orders' => $faker->unique(true)->randomDigit,
            'course_id' => $faker->randomElement($courses)
        ]);
      }
    }
}
