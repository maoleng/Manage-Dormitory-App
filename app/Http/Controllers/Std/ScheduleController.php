<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class ScheduleController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function index(): array
    {
        $now = Carbon::now();
        $start_week = $now->startOfWeek()->format('Y-m-d');
        $end_week = $now->endOfWeek()->format('Y-m-d');
        $schedules = Schedule::query()->whereBetween('date', [$start_week, $end_week])
            ->with('scheduleStudent')
            ->get();

        $data = [];
        foreach ($schedules as $key => $schedule) {
            $data[$key]['date_time'] = $schedule->date->toDateString();
            $data[$key]['period'] = $schedule->period;
            $data[$key]['period_detail'] = $schedule->periodDetail;
            $schedule_students = $schedule->scheduleStudent;
            foreach ($schedule_students as $key1 => $schedule_student) {
                $data[$key]['schedule_student'][$key1]['name'] = $schedule_student->name;
                $data[$key]['schedule_student'][$key1]['student_card_id'] = $schedule_student->student_card_id;
                $data[$key]['schedule_student'][$key1]['room'] = $schedule_student->room->name ?? null;
            }
        }

        return [
            'status' => true,
            'data' => $data
        ];

    }

    public function makeSchedule(): void
    {
        $now = Carbon::now();
        $start_week = $now->startOfWeek()->format('Y-m-d');
        $end_week = $now->endOfWeek()->format('Y-m-d');
        $check = Schedule::query()->whereDate('started_at', $start_week)->first();
        if (empty($check)) {
            $week = CarbonPeriod::create($start_week, $end_week);
            foreach ($week as $date) {
                $date = $date->hour(4)->minute(30);
                for ($i = 1; $i <= 11; $i++) {
                    Schedule::query()->create([
                        'date' => $date,
                        'period' => $i,
                        'started_at' => $date->addMinutes(90)
                    ]);
                }
            }

        }
    }
}
