<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Http\Requests\Std\RegisterScheduleRequest;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use JetBrains\PhpStorm\ArrayShape;

class ScheduleController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function index(): array
    {
        $is_current_week = (bool)request()->query('current_week');

        if ($is_current_week) {
            $periods = Period::query()
                ->with('currentSchedules')
                ->with('currentSchedules.scheduleStudent')
                ->get();
        } else {
            $periods = Period::query()
                ->with('nextSchedules')
                ->with('nextSchedules.scheduleStudent')
                ->get();
        }

        $data = [];
        foreach ($periods as $i => $period) {
            $data[$i]['period'] = $period->period;
            $data[$i]['period_detail'] = $period->periodDetail;
            $schedules = $is_current_week ? $period->currentSchedules: $period->nextSchedules;

            foreach ($schedules as $key => $schedule) {
                $data[$i]['schedules'][$key]['day'] = $schedule->dayOfWeek;
                $data[$i]['schedules'][$key]['date'] = $schedule->dateBeautiful;
                $students = $schedule->scheduleStudent;
                $data[$i]['schedules'][$key]['count_students'] = count($students);
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

    public function checkIfEmptySchedule()
    {
        $periods = $this->index()['data'];
        if (empty($periods)) {
            return [
                'status' => false,
                'message' => 'Ch??a c?? d??? li???u l???ch tr???c'
            ];
        }
        foreach ($periods as $period) {
            $schedules = $period['schedules'];
            foreach ($schedules as $schedule) {
                $count_cur_std = $schedule['count_students'];
                if ($count_cur_std === 0) {
                    return [
                        'status' => true,
                        'data' => 0,
                        'message' => 'Hi???n t???i v???n c?? ca ch??a c?? ai ????ng k??'
                    ];
                }
            }
        }
        foreach ($periods as $period) {
            $schedules = $period['schedules'];
            foreach ($schedules as $schedule) {
                $count_cur_std = $schedule['count_students'];
                if ($count_cur_std === 1) {
                    return [
                        'status' => true,
                        'data' => 1,
                        'message' => 'Hi???n t???i v???n c?? ca ??ang c?? 1 ng?????i ????ng k??'
                    ];
                }

            }
        }
        foreach ($periods as $period) {
            $schedules = $period['schedules'];
            foreach ($schedules as $schedule) {
                $count_cur_std = $schedule['count_students'];
                if ($count_cur_std === 2) {
                    return [
                        'status' => true,
                        'data' => 2,
                        'message' => 'Hi???n t???i c??c ca ?????u c?? 2 ng?????i ????ng k?? tr??? l??n'
                    ];
                }
            }
        }
    }

    public function save(RegisterScheduleRequest $request): array
    {
        if (!Carbon::now()->is('Sunday')) {
            return [
                'status' => false,
                'message' => 'Ch??? c?? th??? ????ng k?? l???ch v??o cu???i tu???n'
            ];
        }

        $student = c('student');
        $next_week = Carbon::now()->next('Monday');
        $start_week = $next_week->startOfWeek()->format('Y-m-d');
        $end_week = $next_week->endOfWeek()->format('Y-m-d');
        $old_schedule_ids = Student::query()->where('id', $student->id)
            ->with('scheduleStudent', function($q) use($start_week, $end_week) {
                $q->whereBetween('date', [$start_week, $end_week]);
            })->first()->scheduleStudent->pluck('id');
        $student->scheduleStudent()->detach($old_schedule_ids);

        $schedule_ids = $request->validated()['schedule_ids'];
        foreach ($schedule_ids as $schedule_id) {
            $student->scheduleStudent()->attach($schedule_id);
        }

        return [
            'status' => true,
        ];

    }

    // support on developing
    public function makeCurrentSchedule(): void
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

    public function makeSchedule(): void
    {
        $next_week = Carbon::now()->next('Monday');
        $start_week = $next_week->startOfWeek()->format('Y-m-d');
        $end_week = $next_week->endOfWeek()->format('Y-m-d');
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
