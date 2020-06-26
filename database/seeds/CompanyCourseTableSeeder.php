<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class CompanyCourseTableSeeder extends Seeder
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
        $companies = DB::table("company")->pluck('id');
        $users = DB::table("users")->pluck('id');

        for($i = 0; $i < 30; $i++){
          DB::table('company_course')->insert([
              'course_id' => $faker->randomElement($courses),
              'company_id' => $faker->randomElement($companies)
          ]);
        }
    }
}
