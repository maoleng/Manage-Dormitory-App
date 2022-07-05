<?php

namespace App\Http\Controllers\Mng;

use App\Http\Requests\Mng\PickRoomRequest;
use App\Models\Contract;
use App\Models\Detail;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subscription;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class ContractController
{
    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])]
    public function all(): array
    {
        return [
            'status' => true,
            'data' => Contract::query()
                ->where('is_accept', true)
                ->with('student')
                ->with('room')
                ->with('subscription')
                ->get()
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function forms(): array
    {
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

    #[ArrayShape(['status' => "false", 'message' => "string"])]
    public function pickRoom(PickRoomRequest $request, $id): array
    {
        // VALIDATE
        $contract = Contract::query()->find($id);
        if (empty($contract)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy hợp đồng'
            ];
        }
        if (!$contract->is_accept) {
            return [
                'status' => false,
                'message' => 'Hợp đồng chưa được duyệt'
            ];
        }
        if (isset($contract->room_id)) {
            return [
                'status' => false,
                'message' => 'Học sinh đã có phòng'
            ];
        }

        // CODE
        $room_id = $request->get('room_id');
        $room = Room::query()->find($room_id);
        if ($room->ifRoomIsMaxium) {
            return [
                'status' => false,
                'message' => 'Phòng đã đủ người'
            ];
        }
        if ($room->ifRoomIsNearlyMaximum) {
            $room->update(['status' => 'Đã hết chỗ']);
        }
        $room->update(['amount' => ++$room->amount]);
        $contract->update(['room_id' => $room_id]);
        Student::query()->find($contract->student_id)->update(['room_id' => $room_id]);

        return [
            'status' => true,
            'message' => 'Xếp phòng cho học sinh thành công'
        ];

    }




}

