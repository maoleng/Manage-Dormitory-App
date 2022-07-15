<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Period;
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
        $periods = Period::query()
            ->with('schedules', function($q) use($start_week, $end_week) {
                $q->whereBetween('schedules.date', [$start_week, $end_week]);
            })
            ->with('schedules.scheduleStudent')
            ->get();
        $data = [];
        foreach ($periods as $i => $period) {
            $data[$i]['period'] = $period->period;
            $data[$i]['period_detail'] = $period->periodDetail;
            $schedules = $period->schedules;
            foreach ($schedules as $key => $schedule) {
                $data[$i]['schedules'][$key]['day'] = $schedule->dayOfWeek;
                $data[$i]['schedules'][$key]['date'] = $schedule->dateBeautiful;
                $students = $schedule->scheduleStudent;
                foreach ($students as $key2 => $student) {
                    $data[$i]['schedules'][$key]['students'][$key2]['id'] = $student->id;
                    $data[$i]['schedules'][$key]['students'][$key2]['name'] = $student->name;
                    $data[$i]['schedules'][$key]['students'][$key2]['student_card_id'] = $student->student_card_id;
                    $data[$i]['schedules'][$key]['students'][$key2]['room'] = $student->room->name ?? null;
                }
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
        $check = Schedule::query()->whereDate('date', $start_week)->first();
        if (empty($check)) {
            $week = CarbonPeriod::create($start_week, $end_week);
            foreach ($week as $date) {
                $date = $date->hour(4)->minute(30);
                for ($i = 1; $i <= 11; $i++) {
                    Schedule::query()->create([
                        'date' => $date,
                        'period_id' => $i
                    ]);
                }
            }

        }
    }
}
