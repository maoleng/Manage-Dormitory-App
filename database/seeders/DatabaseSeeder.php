<?php

namespace Database\Seeders;

use App\Http\Controllers\Std\ContractController;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Form;
use App\Models\Image;
use App\Models\Mistake;
use App\Models\Period;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\Tag;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
    private int $ALL = 1000;
    private int $STUDENT = 995;
    private int $FLOOR = 10;
    private int $FORM = 300;
    private int $MISTAKE = 300;
    private int $FORM_REPORT = 300;
    private int $TAG = 50;
    private int $POST = 100;

//    php artisan migrate:fresh --seed
//    php artisan command:make_current_schedule
//    php artisan command:weekly_schedule_student_guard
//    php artisan db:seed --class=ScheduleSeeder
//    php artisan command:monthly_electricity_water_subscription


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
        $this->createFormRegister();
        $this->createFormConfirmed();
        $this->addStudentToRoom();
        Mistake::factory($this->MISTAKE)->create();
        Form::factory($this->FORM_REPORT)->create();
        Image::factory($this->MISTAKE * 3)->create();
        $this->createFormReply();
        $this->createPeriod();
        Tag::factory($this->TAG)->create();
        Post::factory($this->POST)->create();
        $this->call([
            PostSeeder::class,
        ]);
        $this->createPostTag();
        $this->createAttendance();
        $this->createDefaultStudentTeacher();
    }

    public function createAttendance(): void
    {
        $faker = Faker::create();

        $floors = Floor::query()->whereHas('rooms', function($q) {
            $q->whereHas('students', function($q) {
                $q->where('role', Student::TU_QUAN);
            });
        })->get();
        $guards = [];
        foreach ($floors as $floor) {
            $guards[] = Student::query()->whereHas('room', function($q) use($floor) {
                $q->whereHas('floor', function($q) use($floor) {
                    $q->where('id', $floor->id);
                });
            })->where('role', Student::TU_QUAN)->first();
        }

        $dates = CarbonPeriod::create('13-07-2022', now());
        foreach ($dates as $date) {
            foreach ($guards as $guard) {
                $attendance = Attendance::query()->create([
                    'date' => $date->toDateTimeString(),
                    'guard_id' => $guard->id
                ]);
                $floor_id = $guard->room->floor->id;
                $students = Student::query()->whereHas('room', function($q) use($floor_id) {
                    $q->whereHas('floor', function ($q) use ($floor_id) {
                        $q->where('id', $floor_id);
                    });
                })->get();
                foreach ($students as $student) {
                    AttendanceStudent::query()->create([
                        'attendance_id' => $attendance->id,
                        'student_id' => $student->id,
                        'status' => $faker->numberBetween(0, 2),
                    ]);
                }
            }
        }
    }

    public function createPostTag(): void
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            $tag_id = Tag::query()->inRandomOrder()->value('id');
            $post->tags()->attach($tag_id);
        }
        foreach ($posts as $post) {
            $tag_id = Tag::query()->inRandomOrder()->value('id');
            $post->tags()->attach($tag_id);
        }

    }

    public function createPeriod(): void
    {
        $date = Carbon::now()->hour(4)->minute(30)->second(0);
        for ($i = 1; $i <= 11; $i++) {
            Period::query()->create([
                'period' => $i,
                'started_at' => $date->addMinutes(90)
            ]);
        }
    }

    public function createFormReply(): void
    {
        $parent = Form::query()->create([
            'title' => 'Bị sờ soạng',
            'student_id' => Student::query()->inRandomOrder()->value('id'),
            'content' => 'Em chào thầy, em bị sờ soạng',
        ]);
        Image::query()->create([
            'source' => 'https://thumbs.dreamstime.com/b/cosmos-beauty-deep-space-elements-image-furnished-nasa-science-fiction-art-102581846.jpg',
            'size' => '6969',
            'form_id' => $parent->id
        ]);
        Form::query()->create([
            'title' => 'Bị sờ soạng',
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => 'Thế à, bạn ấy là ai thế',
            'parent_id' => $parent->id,
        ]);
        Form::query()->create([
            'title' => 'Bị sờ soạng',
            'student_id' => $parent->student_id,
            'content' => 'Bạn ấy tên Hùng',
            'parent_id' => $parent->id
        ]);
        Form::query()->create([
            'title' => 'Bị sờ soạng',
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => 'OK em',
            'parent_id' => $parent->id,
        ]);
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
                'end_date' => (new Contract)->getContractEndDate($season),
            ]);
        }
        return $contracts;
    }

    public function createDefaultStudentTeacher(): void
    {
        Information::factory(4)->create();
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
        $student = Student::factory()->create([
            'name' => 'Phạm Minh Trí Hùng',
            'email' => 'student@student.tdtu.edu.vn',
            'student_card_id' => '521H0504',
            'password' => '1234',
            'role' => 'Sinh viên tự quản',
            'information_id' => $this->ALL + 3,
        ]);
        Student::factory()->create([
            'name' => 'Phạm Minh Chí Cường',
            'email' => 'student1@student.tdtu.edu.vn',
            'student_card_id' => '521H0405',
            'password' => '1234',
            'role' => 'Sinh viên tự quản',
            'information_id' => $this->ALL + 4,
        ]);
        $contract = Contract::query()->create([
            'student_id' => $student->id,
            'room_type' => '6',
            'season' => '2ss',
            'start_date' => (new Contract)->getContractStartDate('2ss'),
            'end_date' => (new Contract)->getContractStartDate('2ss'),
        ]);
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
        Room::query()->find(351)->increment('amount');
        $contract->update(['room_id' => 351]);
        $student->update(['room_id' => 351]);
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
            $floor = Floor::factory()->create([
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
                    'floor_id' => $floor->id,
                ]);
            }
        }
        Building::factory()->create(['name' => 'I']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            $floor = Floor::factory()->create([
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
                    'floor_id' => $floor->id,
                ]);
            }
        }
        Building::factory()->create(['name' => 'K']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            $floor = Floor::factory()->create([
                'name' => $i,
                'building_id' => 3,
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
                    'floor_id' => $floor->id,
                ]);
            }
        }
        Building::factory()->create(['name' => 'L']);
        for($i = 1; $i <= $this->FLOOR; $i++) {
            $floor = Floor::factory()->create([
                'name' => $i,
                'building_id' => 4,
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
                    'floor_id' => $floor->id,
                ]);
            }
        }
    }
}
