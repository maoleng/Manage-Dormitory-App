<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\UpdateStudentRequest;
use App\Models\Mistake;
use App\Models\Student;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class StudentController extends Controller
{
    #[ArrayShape(['state' => "bool", 'data' => "mixed"])]
    public function index(Request $request): array
    {
        $room_id = $request->get('room_id');
        if (isset($room_id)) {
            $students = Student::query()->where('room_id', $room_id)->get();
        } else {
            $students = Student::query()->get();
        }

        return [
            'state' => true,
            'data' => $students
        ];
    }

    public function detail($id): array
    {
        $student = Student::query()->where('id', $id)
            ->with('information')
            ->withCount('mistakes')
            ->first();
        if (empty($student)) {
            return [
                'status' => false,
                'messages' => 'Không tìm thấy học sinh'
            ];
        }

        return [
            'status' => true,
            'data' => $student
        ];
    }

    public function update($id, UpdateStudentRequest $request): array
    {
        $data = $request->validated();
        $student = Student::query()->find($id);
        if (empty($student)) {
            return [
                'status' => false,
                'messages' => 'Không tìm thấy học sinh'
            ];
        }
        $student->update($data);

        return [
            'status' => true,
            'data' => $student
        ];
    }
}
