<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Http\Requests\Std\CheckAttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Room;
use App\Models\Student;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class AttendanceController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function getRooms(): array
    {
        $rooms = c('student')->room->floor->rooms;
        $data = [];

        foreach ($rooms as $key => $room) {
            $data[$key]['id'] = $room->id;
            $data[$key]['name'] = $room->name;
            $data[$key]['count_student'] = $room->where('id', $room->id)->withCount('students')->first()->students_count;

            $student_ids = $room->students->pluck('id')->toArray();
            $students = Student::query()->whereIn('id', $student_ids)
                ->with('attendanceStudents', function($q) {
                    $q->whereHas('attendance', function($q) {
                        $q->whereDate('date', Carbon::createFromTimeString('00:00')->toDateTimeString());
                    });
                })->get();
            $check = $students->every(static function($student) {
                return isset($student->attendanceStudents[0]);
            });
            if (!$check || $students->isEmpty()) {
                $data[$key]['is_finish'] = false;
            } else {
                $data[$key]['is_finish'] = true;
            }

        }
        return [
            'status' => true,
            'data' => $data,
        ];
    }

    public function getStudents($id): array
    {
        $room = Room::query()->find($id);
        if (empty($room)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy phòng'
            ];
        }

        $student_ids = $room->students->pluck('id');
        $students = Student::query()->whereIn('id', $student_ids)
            ->with('attendanceStudents', function($q) {
                $q->whereHas('attendance', function($q) {
                    $q->whereDate('date', Carbon::createFromTimeString('00:00')->toDateTimeString());
                });
            })->get();

        $data = [];
        foreach ($students as $key => $student) {
            $data[$key]['id'] = $student->id;
            $data[$key]['student_card_id'] = $student->student_card_id;
            $data[$key]['name'] = $student->name;
            $data[$key]['is_check'] = isset($student->attendanceStudents[0]) ? 1 : 0;
        }

        return [
            'status' => true,
            'data' => $data
        ];
    }

    #[ArrayShape(['status' => "bool"])]
    public function checkAttendance(CheckAttendanceRequest $request): array
    {
        $data = $request->validated();

        $attendance = Attendance::query()->firstOrCreate(
            [
                'date' => Carbon::createFromTimeString('00:00')->toDateTimeString(),
            ],
            [
                'date' => Carbon::createFromTimeString('00:00')->toDateTimeString(),
                'guard_id' => c('student')->id
            ]
        );
        foreach ($data as $each_attendance) {
            AttendanceStudent::query()->firstOrCreate(
                [
                    'attendance_id' => $attendance->id,
                    'student_id' => $each_attendance['student_id'],
                ],
                [
                    'attendance_id' => $attendance->id,
                    'student_id' => $each_attendance['student_id'],
                    'status' => $each_attendance['status'],
                    'note' => $each_attendance['note'],
                ]
            );
        }

        return [
            'status' => true,
        ];

    }

}
