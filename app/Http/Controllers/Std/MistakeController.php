<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Mistake;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class MistakeController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function all(): array
    {
        $student_id = c('student')->id;
        $mistakes = Mistake::query()->with('student.room')->where('student_id', $student_id)->get();
        $arr = [];
        foreach ($mistakes as $mistake) {
            $arr[] = [
                'id' => $mistake->id,
                'teacher_id' => $mistake->teacher->id,
                'teacher_name' => $mistake->teacher->name,
                'type' => $mistake->beautifulType,
                'content' => $mistake->content ?? null,
                'date' => Carbon::make($mistake->date)->format('d-m-Y H:i:s'),
                'room_name' => $mistake->student->room->name ?? null,
                'is_confirmed' => $mistake->is_confirmed,
                'is_fix_mistake' => $mistake->is_fix_mistake,
            ];
        }

        return [
            'status' => true,
            'data' => $arr
        ];
    }

    public function confirm($id): array
    {
        $mistake = Mistake::query()->where('student_id', c('student')->id)->find($id);
        if (empty($mistake)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy vi phạm'
            ];
        }
        $mistake->update(['is_confirmed' => true]);

        return [
            'status' => true,
            'message' => 'Xác nhận lỗi thành công'
        ];
    }
}

