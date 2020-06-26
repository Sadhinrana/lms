<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class StudentCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Models\CompanyCourse::class,15)->create();
        $faker = \Faker\Factory::create();
        $courses = DB::table("course")->pluck('id');
        $users = DB::table("users")->pluck('id');

        for($i = 0; $i < 30; $i++){
          DB::table('student_courses')->insert([
              'course_id' => $faker->randomElement($courses),
              'student_id' => $faker->randomElement($users)
          ]);
        }
    }
}
