<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
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

        return [
            'status' => true,
            'data' => $room->students->toArray()
        ];
    }

    public function createAttendance()
    {

    }

}
