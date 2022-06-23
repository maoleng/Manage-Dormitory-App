<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): array // TODO: validate request
    {
        $data = $request->all();
        $teacher = Teacher::query()
            ->where('email', $data['username'])
            ->where('password', $data['password'])
            ->first();

        if (isset($teacher)) {
            return $this->checkDevice($teacher, $data['device_id']);
        }

        return [
            'status' => false,
            'message' => 'Sai tài khoản hoặc mật khẩu'
        ];
    }


    public function checkDevice($teacher, $device_id): array
    {
        $check = Device::query()->where('teacher_id', $teacher->id)->where('device_id', $device_id)->first();
        if (empty($check)) {
            $token = Str::random(70) . time() . Str::random(20);
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
