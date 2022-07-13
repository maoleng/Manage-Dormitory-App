<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $start_week = $now->startOfWeek()->format('Y-m-d');
        $end_week = $now->endOfWeek()->format('Y-m-d');
        $schedules = Schedule::query()->whereBetween('date', [$start_week, $end_week])
            ->get();
        $temp = 0;
        $count = 0;
        foreach ($schedules as $schedule) {
            $student_id = Subscription::query()->inRandomOrder()->value('student_id');
            while ($student_id === $temp) {
                $student_id = Subscription::query()->inRandomOrder()->value('student_id');
            }
            $schedule->scheduleStudent()->attach($student_id);
            $temp = $student_id;
        }
        foreach ($schedules as $schedule) {
            $count++;
            $student_id = Subscription::query()->inRandomOrder()->value('student_id');
            while ($student_id === $temp) {
                $student_id = Subscription::query()->inRandomOrder()->value('student_id');
            }
            $schedule->scheduleStudent()->attach($student_id);
            $temp = $student_id;
            if ($count === 35) {
                break;
            }
        }
    }
}
