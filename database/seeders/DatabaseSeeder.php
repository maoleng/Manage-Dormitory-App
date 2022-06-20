<?php

namespace Database\Seeders;

use App\Models\Information;
use App\Models\Student;
use App\Models\Teacher;
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
        Information::factory(100)->create();
        Teacher::factory(30)->create();
        Student::factory(70)->create();
    }
}
