<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Detail;
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

        Detail::factory()->create([
            'max' => 8,
            'price_per_month' => "250000",
            'description' => "Phòng 8 tối đa 8 người...",
        ]);
        Detail::factory()->create([
            'max' => 6,
            'price_per_month' => "650000",
            'description' => "Phòng 6 tối đa 6 người...",
        ]);
        Detail::factory()->create([
            'max' => 4,
            'price_per_month' => "3000000",
            'description' => "Phòng 4 tối đa 4 người...",
        ]);
        Detail::factory()->create([
            'max' => 2,
            'price_per_month' => "5000000",
            'description' => "Phòng 2 tối đa 2 người...",
        ]);

        Building::factory()->create(['name' => 'H']);
        for($i = 1; $i <= 5; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 1,
            ]);
            for($j = 1; $j <= 9; $j++) {
                Room::factory()->create([
                    'name' => 'H' . '0' . $i . '0' . $j,
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
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
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'floor_id' => $i,
                ]);
            }
        }
    }
}
