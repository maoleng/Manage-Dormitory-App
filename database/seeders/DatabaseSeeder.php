<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Mistake;
use App\Models\Subscription;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Detail;
use App\Models\Floor;
use App\Models\Information;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private int $ALL = 5000;
    private int $STUDENT = 4950;
    private int $FLOOR = 17;
    private int $FORM = 500;
    private int $MISTAKE = 500;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Information::factory($this->ALL)->create();
        Teacher::factory($this->ALL - $this->STUDENT)->create();
        Student::factory($this->STUDENT)->create();

        $this->createDetail();
        $this->createBuildingFloorRoom();

        $this->createDefaultStudentTeacher();
        $this->createFormRegister();
        $this->createFormConfirmed();
        $this->addStudentToRoom();
        Mistake::factory($this->MISTAKE)->create();
        Image::factory($this->MISTAKE * 3)->create();

    }

    public function addStudentToRoom(): void
    {
        $contracts = Contract::query()->whereNotNull('subscription_id')->get();
        foreach ($contracts as $contract) {
            $room_id = Room::query()
                ->where('detail_id', $contract->roomDetailId)
                ->inRandomOrder()
                ->value('id');
            $room = Room::query()->find($room_id);
            if ($room->ifRoomIsMaximum) {
                continue;
            }
            if($room->ifRoomIsNearlyMaximum) {
                $room->update(['status' => 'Đã hết chỗ']);
            }
            Room::query()->find($room_id)->increment('amount');
            $contract->update(['room_id' => $room_id]);
            Student::query()->find($contract->student_id)->update(['room_id' => $room_id]);
        }

    }

    public function createFormConfirmed(): array
    {
        $contracts = $this->createFormRegister();
        foreach ($contracts as $contract) {
            $room_detail = Detail::query()->where('max', $contract->room_type)->first();
            $subscription = Subscription::query()->create([
                'student_id' => $contract->student_id,
                'type' => Subscription::CONTRACT ,
                'price' => $room_detail->getTotalMoney($contract->season),
                'pay_start_time' => Carbon::now(),
                'pay_end_time' => Carbon::now()->addDays(7)
            ]);
            Contract::query()->where('id', $contract->id)->update([
                'is_accept' => true,
                'subscription_id' => $subscription->id
            ]);
        }
        return $contracts;
    }

    public function createFormRegister(): array
    {
        $faker = Faker::create();
        for ($i = 1; $i <= $this->FORM; $i++) {
            $student_ids = Contract::query()->pluck('student_id')->toArray();
            $season = $faker->randomElement(['ss1', 'ss2', '2ss', 'summer']);
             $contracts[] = Contract::query()->create([
                'student_id' => Student::query()->whereNotIn('id', $student_ids)->inRandomOrder()->value('id'),
                'room_type' => $faker->randomElement([2,4,6,8]),
                'season' => $season,
                'start_date' => (new Contract)->getContractStartDate($season),
                'end_date' => (new Contract)->getContractStartDate($season),
            ]);
        }
        return $contracts;
    }

    public function createDefaultStudentTeacher(): void
    {
        Information::factory(3)->create();
        Teacher::factory()->create([
            'name' => 'Bùi Quy Oanh',
            'email' => 'tuquan@teacher.tdtu.edu.vn',
            'password' => '1234',
            'role' => 'Thầy tự quản',
            'information_id' => $this->ALL + 1,
        ]);
        Teacher::factory()->create([
            'name' => 'Mai Văn Mạnh',
            'email' => 'quanly@teacher.tdtu.edu.vn',
            'password' => '1234',
            'role' => 'Quản lý kí túc xá',
            'information_id' => $this->ALL + 2,
        ]);
        Student::factory()->create([
            'name' => 'Phạm Minh Trí Hùng',
            'email' => 'student@student.tdtu.edu.vn',
            'student_card_id' => '521H0504',
            'password' => '1234',
            'role' => 'Sinh viên tự quản',
            'information_id' => $this->ALL + 3,
        ]);
    }

    public function createDetail(): void
    {
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
    }

    public function createBuildingFloorRoom(): void
    {
        Building::factory()->create(['name' => 'H']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 1,
            ]);
            for($j = 1; $j <= 9; $j++) {
                if ($i >= 10) {
                    $name = 'H' . $i . '0' . $j;
                } else {
                    $name = 'H' . '0' . $i . '0' . $j;
                }
                Room::factory()->create([
                    'name' => $name,
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'floor_id' => $i,
                ]);
            }
        }
        Building::factory()->create(['name' => 'I']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 2,
            ]);
            for($j = 1; $j <= 9; $j++) {
                if ($i >= 10) {
                    $name = 'I' . $i . '0' . $j;
                } else {
                    $name = 'I' . '0' . $i . '0' . $j;
                }
                Room::factory()->create([
                    'name' => $name,
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'floor_id' => $i,
                ]);
            }
        }
        Building::factory()->create(['name' => 'K']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 2,
            ]);
            for($j = 1; $j <= 9; $j++) {
                if ($i >= 10) {
                    $name = 'K' . $i . '0' . $j;
                } else {
                    $name = 'K' . '0' . $i . '0' . $j;
                }
                Room::factory()->create([
                    'name' => $name,
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'floor_id' => $i,
                ]);
            }
        }
        Building::factory()->create(['name' => 'L']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            Floor::factory()->create([
                'name' => $i,
                'building_id' => 2,
            ]);
            for($j = 1; $j <= 9; $j++) {
                if ($i >= 10) {
                    $name = 'L' . $i . '0' . $j;
                } else {
                    $name = 'L' . '0' . $i . '0' . $j;
                }
                Room::factory()->create([
                    'name' => $name,
                    'detail_id' => Detail::query()->inRandomOrder()->value('id'),
                    'amount' => 0,
                    'status' => 'Còn trống chỗ',
                    'floor_id' => $i,
                ]);
            }
        }
    }
}
