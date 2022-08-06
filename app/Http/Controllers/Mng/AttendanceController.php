<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class AttendanceController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function index(Request $request): array
    {
        Carbon::setLocale('vi');
        $query_params = $request->all();
        $check_time = now() > now()->hour(22)->minute(30);
        $time = empty($query_params['time']) ? ($check_time ? now() : now()->subDay()) : Carbon::create($query_params['time']);
        $status = isset($query_params['status']) ? (int)$query_params['status'] : null;
        $building_id = $query_params['building_id'] ?? null;
        $floor_id = $query_params['floor_id'] ?? null;
        $room_id = $query_params['room_id'] ?? null;
        $date = $time->toDate();

        $students = Student::query()
            ->whereHas('attendanceStudents.attendance', function ($q) use ($date) {
                $q->whereDate('date', $date);
            })
            ->with('attendanceStudents', function ($q) use ($date) {
                $q->whereHas('attendance', function ($q) use ($date) {
                    $q->whereDate('date', $date);
                });
            })
            ->with('room');

        if (isset($building_id)) {
            $students = $students->whereHas('room.floor.building', function ($q) use ($building_id) {
                $q->where('id', $building_id);
            });
        }
        if (isset($floor_id)) {
            $students = $students->whereHas('room.floor', function ($q) use ($floor_id) {
                $q->where('id', $floor_id);
            });
        }
        if (isset($room_id)) {
            $students = $students->where('room_id', $room_id);
        }

        if (isset($status) || $status === 0) {
            $students = $students->whereHas('attendanceStudents', function ($q) use ($date, $status) {
                $q->whereHas('attendance', function ($q) use ($date) {
                    $q->whereDate('date', $date);
                })->where('status', $status);
            })->get();
        } else {
            $students = $students->get();
        }

        $students = $students->map(static function ($student) {
            return [
                'id' => $student->id,
                'student_card_id' => $student->student_card_id,
                'name' => $student->name,
                'room' => $student->room->name,
                'role' => $student->role,
                'status' => $student->attendanceStudents[0]->rawStatus,
                'note' => $student->attendanceStudents[0]->note,
            ];
        });

        return [
            'status' => true,
            'data' => [
                'title' => 'Danh sách điểm danh sinh viên mỗi tối vào ' . $time->longRelativeDiffForHumans(now()->format('Y')),
                'students' => $students,
            ]
        ];

    }

    public function detailStudent($id): array
    {
        $student = Student::query()
            ->withCount('attendancePermission')
            ->withCount('attendanceNoPermission')
            ->find($id);
        $student_return = $student->toArray();
        if (empty($student)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy học sinh'
            ];
        }
        $absentAttendances = $student->absentAttendances->map(static function ($attendance) {
            return [
                'status' => $attendance->rawStatus,
                'note' => $attendance->note,
                'date' => $attendance->attendance->date,
                'guard_student' => $attendance->attendance->guardStudent,
            ];
        });

        return [
            'status' => true,
            'data' => [
                'student' => $student_return,
                'attendance' => $absentAttendances
            ]
        ];

    }


}
