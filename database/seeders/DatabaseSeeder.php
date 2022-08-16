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
use Bluemmb\Faker\PicsumPhotosProvider;
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
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class DatabaseSeeder extends Seeder
{
    private int $ALL = 5000;
    private int $STUDENT = 4950;
    private int $FLOOR = 10;
    private int $FORM = 1300;
    private int $MISTAKE = 2000;
    private int $FORM_REPORT = 300;
    private int $TAG = 50;
    private int $POST = 500;

/**
php artisan migrate:fresh --seed

 * dùng trong quá trình dev, tạo lịch trong tuần hiện tại
php artisan command:make_current_schedule

 * tạo lịch cho tuần tới
php artisan command:weekly_schedule_student_guard

 * Tạo các mẫu đăng kí lịch cho tuần hiện tại
php artisan db:seed --class=ScheduleSeeder

 * Tạo các mẫu đăng kí lịch cho tuần sau
php artisan db:seed --class=ScheduleNextWeekSeeder

php artisan command:monthly_electricity_water_subscription
*/

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->createInformation($this->ALL);
        $this->createTeacher($this->ALL - $this->STUDENT);
        $this->createStudent($this->STUDENT);
        $this->createDetail();
        $this->createBuildingFloorRoom();
        $this->createFormRegister();
        $this->createFormConfirmed();
        $this->addStudentToRoom();
        $this->createMistake($this->MISTAKE);
        $this->createForm($this->FORM_REPORT);
        $this->createImage($this->MISTAKE * 3);
        $this->createFormReply();
        $this->createPeriod();
        $this->createTag($this->TAG);
        $this->createPost($this->POST);
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

        $dates = CarbonPeriod::create('13-06-2022', now());
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
        $periods = [];
        for ($i = 1; $i <= 11; $i++) {
            $periods[] = [
                'period' => $i,
                'started_at' => $date->addMinutes(90)
            ];
        }
        DB::disableQueryLog();
        DB::table('periods')->insert($periods);
    }

    public function createFormReply(): void
    {
        $parent = Form::query()->create([
            'title' => 'Bị sờ soạng',
            'student_id' => Student::query()->where('role', '!=', Student::SINH_VIEN)->inRandomOrder()->value('id'),
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
        $faker = Faker::create();
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
            $roles = (new ReflectionClass(Student::class))->getConstants();
            unset($roles['CREATED_AT'], $roles['UPDATED_AT'], $roles['SINH_VIEN']);
            $subscription->student->update(['role' => $faker->randomElement($roles)]);
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
                'student_id' => Student::query()
                    ->where('role', Student::SINH_VIEN)
                    ->whereNotIn('id', $student_ids)->inRandomOrder()->value('id'),
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
        DB::disableQueryLog();
        DB::table('details')->insert([
            [
                'max' => 8,
                'price_per_month' => "250000",
                'description' => "Phòng 8 tối đa 8 người...",
            ],
            [
                'max' => 6,
                'price_per_month' => "650000",
                'description' => "Phòng 6 tối đa 6 người...",
            ],
            [
                'max' => 4,
                'price_per_month' => "3000000",
                'description' => "Phòng 4 tối đa 4 người...",
            ],
            [
                'max' => 2,
                'price_per_month' => "5000000",
                'description' => "Phòng 2 tối đa 2 người...",
            ]
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

    public function createInformation($count): void
    {
        $faker = Faker::create();
        $ethnics = [
            'Kinh', 'Tày', 'Thái', 'Hoa', 'Khơ me', 'Mường', 'Nùng', 'HMông', 'Dao', 'Gia rai', 'Ngái', 'Ê đê', 'Ba na', 'Xơ Đăng', 'Sán Chay', 'Cơ ho', 'Chăm', 'Sán Dìu', 'Hrê', 'Mnông', 'Ra glai', 'Xtiêng', 'Bru Vân Kiều', 'Thổ', 'Giáy', 'Cơ tu', 'Gié Triêng', 'Mạ', 'Khơ mú', 'Co', 'Tà ôi', 'Chơ ro', 'Kháng', 'Xinh mun', 'Hà Nhì', 'Chu ru', 'Lào', 'La Chí', 'La Ha', 'Phù Lá', 'La Hủ', 'Lự', 'Lô Lô', 'Chứt', 'Mảng', 'Pà Thẻn', 'Co Lao', 'Cống', 'Bố Y', 'Si La', 'Pu Péo', 'Brâu', 'Ơ Đu', 'Rơ'
        ];
        $informations = [];
        for ($i = 1; $i <= $count; $i++) {
            $informations[] = [
                'birthday' => $faker->dateTime,
                'gender' => $faker->numberBetween(0,1),
                'birthplace' => $faker->address,
                'ethnic' => $faker->randomElement($ethnics),
                'religion' => $faker->randomElement(['Không', 'Phật giáo']),
                'phone' => $faker->phoneNumber,
                'identify_card' => $faker->creditCardNumber,
                'address' => $faker->address,
                'area' => $faker->randomElement(['KV1', 'KV2', 'KV3']),
            ];
        }
        DB::disableQueryLog();
        DB::table('information')->insert($informations);
    }

    public function createStudent($count): void
    {
        $faker = Faker::create();
        $students = [];
        $teacher_information_ids = Teacher::query()->get('information_id')->pluck('information_id')->toArray();
        $information_ids = Information::query()->whereNotIn('id', $teacher_information_ids)->get()->pluck('id')->toArray();
        for ($i = 1; $i <= $count; $i++) {
            $student_card_id = $faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'H'])
                .$faker->numberBetween(15, date("y")).$faker->randomElement([0, 'F', 'H'])
                .$faker->numberBetween(1000, 9999);
            $students[] = [
                'name' => $faker->name,
                'email' => $student_card_id.'@student.tdtu.edu.vn',
                'student_card_id' => $student_card_id,
                'password' => '$2y$10$xl9U7eqCieBjSdGh/hn44.fHGrvUifZVybb.vshzd.joKRujyK2KC',
                'role' => Student::SINH_VIEN,
                'information_id' => $faker->randomElement($information_ids),
            ];
        }
        DB::disableQueryLog();
        DB::table('students')->insert($students);
    }

    public function createTeacher($count): void
    {
        $faker = Faker::create();
        $teachers = [];
        $teacher_id = $faker->numberBetween(100, 999) . strtoupper($faker->randomLetter) . $faker->numberBetween(1000, 9999);
        for ($i = 1; $i <= $count; $i++) {
            $teachers[] = [
                'name' => $faker->name,
                'email' => $teacher_id . '@teacher.tdtu.edu.vn',
                'password' => "1234",
                'role' => $faker->randomElement(['Thầy tự quản', 'Quản lý kí túc xá']),
                'information_id' => $faker->numberBetween(1, $this->ALL),
            ];
        }
        DB::disableQueryLog();
        DB::table('teachers')->insert($teachers);
    }

    public function createMistake($count): void
    {
        $faker = Faker::create();
        $mistakes = [];
        $student_ids = Contract::query()->whereNotNull('subscription_id')->get()->pluck('student_id')->toArray();
        for ($i = 1; $i <= $count; $i++) {
            $random_type = $faker->numberBetween(1, 10);
            $random_bool = $faker->numberBetween(0, 1);
            $random_date = $faker->randomElement([2021, 2022, 2022, 2022, 2022]) . '-' . $faker->date($format = 'm-d', $max = 'now') . ' ' . $faker->time($format = 'H:i:s', $max = 'now');
            $mistakes[] = [
                'student_id' => $faker->randomElement($student_ids),
                'teacher_id' => $faker->numberBetween(1, $this->ALL - $this->STUDENT),
                'type' => $random_type,
                'content' => $random_type === 10 ? $faker->sentence($nbWords = 6, $variableNbWords = true) : null,
                'is_fix_mistake' => $random_bool,
                'is_confirmed' => $random_bool === 1 ? 1 : 0,
                'date' => $random_date
            ];
        }
        DB::disableQueryLog();
        DB::table('mistakes')->insert($mistakes);
    }

    public function createForm($count): void
    {
        $forms = [];
        $faker = Faker::create();
        $student_ids = Contract::query()
            ->where('is_accept', true)
            ->get()->pluck('student_id')->toArray();
        for ($i = 1; $i <= $count; $i++) {
            $forms[] = [
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'student_id' => $faker->randomElement($student_ids),
                'content' => $faker->text($maxNbChars = 200)
            ];
        }
        DB::disableQueryLog();
        DB::table('forms')->insert($forms);
    }

    public function createImage($count): void
    {
        $faker = Faker::create();
        $images = [];
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $mistake_ids = Mistake::query()->get()->pluck('id')->toArray();
        $form_ids = Form::query()->get()->pluck('id')->toArray();
        for ($i = 1; $i <= $count; $i++) {
            $mistake_or_form = random_int(0, 1);
            if ($mistake_or_form === 1) {
                $images[] = [
                    'source' => $faker->imageUrl(640, 480, $faker->numberBetween(1, 1050)),
                    'size' => $faker->numberBetween(10000, 100000),
                    'mistake_id' => $faker->randomElement($mistake_ids),
                    'form_id' => null,
                ];
            } else {
                $images[] = [
                    'source' => $faker->imageUrl(640, 480, $faker->numberBetween(1, 1050)),
                    'size' => $faker->numberBetween(10000, 100000),
                    'form_id' => $faker->randomElement($form_ids),
                    'mistake_id' => null,
                ];
            }
        }
        DB::disableQueryLog();
        DB::table('images')->insert($images);
    }

    public function createTag($count): void
    {
        $faker = Faker::create();
        $tags = [];
        for ($i = 1; $i <= $count; $i++) {
            $tags[] = [
                'name' => $faker->jobTitle,
                'color' => $faker->hexColor
            ];
        }
        DB::disableQueryLog();
        DB::table('tags')->insert($tags);
    }

    public function createPost($count): void
    {
        $faker = Faker::create();
        $posts = [];
        $image_ids = Image::query()->get()->pluck('id')->toArray();
        $teacher_ids = Teacher::query()->get()->pluck('id')->toArray();
        for ($i = 1; $i <= $count; $i++) {
            $posts[] = [
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'content' => $faker->randomHtml(),
                'banner_id' => $faker->randomElement($image_ids),
                'category' => $faker->randomElement([2, 3, 4]),
                'teacher_id' => $faker->randomElement($teacher_ids),
            ];
        }
        DB::disableQueryLog();
        DB::table('posts')->insert($posts);
    }
}
