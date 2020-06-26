<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class,15)->create();

        $faker = \Faker\Factory::create();
        $companies = DB::table("company")->pluck('id');

          DB::table('users')->insert([
            'first_name' => "Student",
            'last_name'  => "Demo",
            'email' => 'student@test.com',
            'role' => 'student',
            'password' => '$2y$10$Aia7k3Z/iCfP6GAEhwP3TOm/pBJzUQ8D/4a2yeGHLrgbZipEBrpAu', // secret
            'company_id' => $faker->randomElement($companies),
          ]);

          DB::table('users')->insert([
            'first_name' => "Instructor",
            'last_name'  => "Demo",
            'email' => 'instructor@test.com',
            'role' => 'instructor',
            'password' => '$2y$10$Aia7k3Z/iCfP6GAEhwP3TOm/pBJzUQ8D/4a2yeGHLrgbZipEBrpAu', // secret
            'company_id' => $faker->randomElement($companies),
          ]);

          DB::table('users')->insert([
            'first_name' => "CompanyHead",
            'last_name'  => "Demo",
            'email' => 'company_head@test.com',
            'role' => 'company_head',
            'password' => '$2y$10$Aia7k3Z/iCfP6GAEhwP3TOm/pBJzUQ8D/4a2yeGHLrgbZipEBrpAu', // secret
            'company_id' => $faker->randomElement($companies),
          ]);

          DB::table('users')->insert([
            'first_name' => "ContentManager",
            'last_name'  => "Demo",
            'email' => 'content_manager@test.com',
            'role' => 'content_manager',
            'password' => '$2y$10$Aia7k3Z/iCfP6GAEhwP3TOm/pBJzUQ8D/4a2yeGHLrgbZipEBrpAu', // secret
            'company_id' => $faker->randomElement($companies),
          ]);

          DB::table('users')->insert([
            'first_name' => "Headmaster",
            'last_name'  => "Demo",
            'email' => 'headmaster@test.com',
            'role' => 'headmaster',
            'password' => '$2y$10$Aia7k3Z/iCfP6GAEhwP3TOm/pBJzUQ8D/4a2yeGHLrgbZipEBrpAu', // secret
            'company_id' => $faker->randomElement($companies),
          ]);
    }
}
