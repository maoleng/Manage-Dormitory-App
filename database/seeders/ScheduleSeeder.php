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
        $student_ids = Subscription::query()
            ->whereNotNull('student_id')
            ->inRandomOrder()
            ->limit(count($schedules))->get('student_id')->toArray();
        foreach ($schedules as $key => $schedule) {
            $schedule->scheduleStudent()->attach($student_ids[$key]['student_id']);
            unset($student_ids[$key]);
        }
        // PART 2
        $student_ids = Subscription::query()
            ->whereNotNull('student_id')
            ->inRandomOrder()
            ->limit(35)->get('student_id')->toArray();
        foreach ($schedules as $key => $schedule) {
            if ($key === 35) {
                break;
            }
            $schedule->scheduleStudent()->attach($student_ids[$key]['student_id']);
            unset($student_ids[$key]);
        }
    }
}
