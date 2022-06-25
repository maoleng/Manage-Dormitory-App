<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\StoreContractRequest;
use App\Models\Contract;
use App\Models\Detail;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class ContractController extends Controller
{

    #[ArrayShape(['status' => "bool", 'room_types' => "mixed", 'register_time' => "string[]"])]
    public function form(): array
    {
        $room_types = Detail::all()->toArray();
        $register_time = $this->getTimeRegister();
        return [
            'status' => true,
            'room_types' => $room_types,
            'register_time' => $register_time
        ];
    }

    public function register(StoreContractRequest $request): array
    {
        $data = $request->validated();
        $student = c('student');

        if (array_keys($this->getTimeRegister())[0] !== $data['season_time']) {
            return [
                'status' => false,
                'message' => 'Sai thời gian đăng ký'
            ];
        }
        // TODO: check khi nào có thể đăng ký hợp đồng tiếp tục
        if (Contract::query()->where('student_id', $student->id)->first() !== null) {
            return [
                'status' => false,
                'message' => 'Đã đăng ký một hợp đồng trước đó'
            ];
        }
        $create = Contract::query()->create([
            'student_id' => $student->id,
            'room_type' => $data['room_type'],
            'season' => $data['season_time'],
            'start_date' => (new Contract)->getContractStartDate($data['season_time']),
            'end_date' => (new Contract)->getContractStartDate($data['season_time']),
        ]);
        return [
            'status' => true,
            'data' => [
                'contract_id' => $create->id,
                'register_time' => $create->created_at
            ]
        ];
    }


    public function registration(): array
    {
        $id = c('student')->id;
        $data = Contract::query()->where('student_id', $id)->with('student')->first();

        if (empty($data)) {
            return [
                'status' => false,
                'message' => 'Học sinh chưa đăng ký kí túc xá'
            ];
        }

        return [
            'status' => true,
            'contract_id' => $data->id,
            'data' => [
                'student_id' => $data->student->student_card_id,
                'name' => $data->student->name,
                'register_time' => $data->created_at,
                'registration_status' => $data->contractStatus
            ]
        ];
    }

    public function getTimeRegister(): string
    {
        $now = Carbon::now();

        $dt = Carbon::now();
        $start_ss1 = $dt->day(13)->month(9)->year($now->year-1)->toDateTimeString();
        $end_ss1 = $dt->day(23)->month(1)->year($now->year)->toDateTimeString();
        $start_ss2 = $dt->day(7)->month(2)->year($now->year)->toDateTimeString();
        $end_ss2 = $dt->day(19)->month(6)->year($now->year)->toDateTimeString();
        $start_summer = $dt->day(20)->month(6)->year($now->year)->toDateTimeString();
        $end_summer = $dt->day(21)->month(6)->year($now->year)->toDateTimeString();
        switch ($now) {
            case $now->between($end_ss1, $start_ss2):
            case $now->between($start_ss1, $end_ss1):
                return 'ss2';
            case $now->between($end_ss2, $start_summer):
            case $now->between($start_ss2, $end_ss2):
                return 'summer';
            case $now->between($start_summer, $end_summer):
                return 'except';
            default:
                return '2ss';
        }
    }
}
