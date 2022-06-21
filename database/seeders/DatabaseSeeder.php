<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Information;
use App\Models\Room;
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

        Building::factory()->create(['name' => 'H']);
        for($i = 1; $i <= 5; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 1,
            ]);
            for($j = 1; $j <= 9; $j++) {
                Room::factory()->create([
                    'name' => 'H' . '0' . $i . '0' . $j,
                    'type' => array_rand(['8', '6', '4', '2'], 1),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'lead_id' => null,
                    'floor_id' => $i,
                ]);
            }
        }

        Building::factory()->create(['name' => 'I']);
        for($i = 1; $i <= 5; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 2,
            ]);
            for($j = 1; $j <= 9; $j++) {
                Room::factory()->create([
                    'name' => 'I' . '0' . $i . '0' . $j,
                    'type' => array_rand(['8', '6', '4', '2'], 1),                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'lead_id' => null,
                    'floor_id' => $i,
                ]);
            }
        }
    }
}
