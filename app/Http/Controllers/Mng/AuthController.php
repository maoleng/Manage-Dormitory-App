<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Lib\JWT\JWT;
use App\Models\Device;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): array // TODO: validate request
    {
        $data = $request->all();
        $teacher = $this->auth($data['username'], $data['password']);

        if (isset($teacher)) {
            return $this->checkDevice($teacher, $data['device_id']);
        }

        return [
            'status' => false,
            'message' => 'Sai tài khoản hoặc mật khẩu'
        ];
    }

    public function auth($username, $password): Teacher|null
    {
        $teacher = Teacher::query()->where('email', $username)->first();
        if ($teacher instanceof Teacher && $teacher->verify($password)) {
            return $teacher;
        }
        return null;
    }

    public function checkDevice($teacher, $device_id): array
    {
        $check = Device::query()->where('teacher_id', $teacher->id)->where('device_id', $device_id)->first();
        if (empty($check)) {
            $jwt = c(JWT::class);
            $token = $jwt->encode([
                'id' => $teacher->id,
                'student_card_id' => $teacher->student_card_id,
                'name' => $teacher->name,
                'role' => $teacher->role,
            ]);
            Device::query()->create([
                'device_id' => $device_id,
                'token' => $token,
                'teacher_id' => $teacher->id,
            ]);
            return [
                'status' => true,
                'token' => $token,
                'role' => $teacher->role,
            ];
        }
        return [
            'status' => true,
            'token' => $check->token,
            'role' => $teacher->role,
        ];
    }
}
