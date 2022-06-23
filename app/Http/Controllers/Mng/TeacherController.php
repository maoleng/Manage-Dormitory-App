<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use JetBrains\PhpStorm\ArrayShape;

class TeacherController extends Controller
{
    #[ArrayShape(['status' => "bool", 'message' => "string"])]
    public function index(): array
    {
        return [
            'status' => true,
            'message' => "Đây là nơi sau khi giáo viên đăng nhập mới có thể vào được",
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function me(): array
    {
        $full_info = Teacher::query()->where('id', c('teacher')->id)->with('information')->first();
        return [
            'status' => true,
            'data' => $full_info
        ];
    }
}
