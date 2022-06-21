<?php

namespace App\Http\Controllers\App;

use App\Models\Student;
use JetBrains\PhpStorm\ArrayShape;

class StudentController
{
    #[ArrayShape(['status' => "bool", 'message' => "string"])]
    public function index(): array
    {
        return [
            'status' => true,
            'message' => "Đây là nơi sau khi học sinh đăng nhập mới có thể vào được",
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function me(): array
    {
        $full_info = Student::query()->where('id', c('user')->id)->with('information')->first();
        return [
            'status' => true,
            'data' => $full_info
        ];
    }
}
