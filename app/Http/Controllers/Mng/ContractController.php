<?php

namespace App\Http\Controllers\Mng;

use App\Models\Contract;
use App\Models\Teacher;

class ContractController
{
    public function forms(): array
    {
        if(!$this->checkRole()) {
            return [
                'status' => false,
                'message' => 'Không phải quản lý kí túc xá'
            ];
        }

        $contracts = Contract::query()->where('is_accept', false)->with('student')->get();
        $data = [];
        foreach ($contracts as $contract) {
            $data[] = [
                'contract_id' => $contract->id,
                'student_id' => $contract->student->student_card_id,
                'name' => $contract->student->name,
                'season' => $contract->beautifulSeason,
                'room_type' => $contract->beautifulRoomType,
                'register_time' => $contract->created_at,
            ];
        }
        return [
            'status' => true,
            'data' => $data
        ];
    }

    public function checkRole(): bool
    {
        $teacher = c('teacher');
        if (Teacher::QUAN_LY !== $teacher->role) {
            return false;
        }
        return true;
    }
}
