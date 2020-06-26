<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanyTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(CourseTableSeeder::class);
        $this->call(CompanyCourseTableSeeder::class);
        $this->call(StudentCourseTableSeeder::class);
        $this->call(LessonTableSeeder::class);
    }
}
