<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Lib\JWT\JWT;
use App\Models\Device;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): array  // TODO: validate request
    {
        $data = $request->all();
        $student = Student::query()
            ->where('email', $data['username'])
            ->where('password', $data['password'])
            ->first();

        if (isset($student)) {
            return $this->checkDevice($student, $data['device_id']);
        }

        return [
            'status' => false,
            'message' => 'Sai tài khoản hoặc mật khẩu'
        ];
    }

    public function checkDevice($student, $device_id): array
    {
        $check = Device::query()->where('student_id', $student->id)->where('device_id', $device_id)->first();
        if (empty($check)) {
            $jwt = c(JWT::class);
            $token = $jwt->encode([
                'id' => $student->id,
                'student_card_id' => $student->student_card_id,
                'name' => $student->name,
                'role' => $student->role,
            ]);
            Device::query()->create([
                'device_id' => $device_id,
                'token' => $token,
                'student_id' => $student->id,
            ]);
            return [
                'status' => true,
                'token' => $token,
                'role' => $student->role,
            ];
        }
        return [
            'status' => true,
            'token' => $check->token,
            'role' => $student->role,
        ];
    }
}
