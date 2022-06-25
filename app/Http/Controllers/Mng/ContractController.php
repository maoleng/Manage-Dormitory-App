<?php

namespace App\Http\Controllers\Mng;

use App\Models\Contract;
use App\Models\Detail;
use App\Models\Subscription;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ContractController
{
    public function all(): Collection
    {
        return Contract::query()->where('is_accept', true)->get();
    }


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
                'start_date' => $contract->start_date,
                'end_date' => $contract->end_date,
                'room_type' => $contract->beautifulRoomType,
                'register_time' => $contract->created_at,
            ];
        }
        return [
            'status' => true,
            'data' => $data
        ];
    }

    public function formConfirm($id): array
    {
        // VALIDATE
        if(!$this->checkRole()) {
            return [
                'status' => false,
                'message' => 'Không phải quản lý kí túc xá'
            ];
        }
        $contract = Contract::query()->find($id);
        if (empty($contract)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy hợp đồng'
            ];
        }
        if ($contract->is_accept) {
            return [
                'status' => false,
                'message' => 'Hợp đồng đã được duyệt từ trước'
            ];
        }

        $room_detail = Detail::query()->where('max', $contract->room_type)->first();
        $subscription = Subscription::query()->create([
            'student_id' => $contract->student_id,
            'type' => Subscription::CONTRACT,
            'price' => $room_detail->getTotalMoney($contract->season),
            'pay_start_time' => Carbon::now(),
            'pay_end_time' => Carbon::now()->addDays(7)
        ]);
        Contract::query()->where('id', $id)->update([
            'is_accept' => true,
            'subscription_id' => $subscription->id
        ]);

        return [
            'status' => true,
            'success' => 'Duyệt đơn đăng ký thành công'
        ];


    }

    public function checkRole(): bool
    {
        $teacher = c('teacher');
        return Teacher::QUAN_LY === $teacher->role;
    }
}

