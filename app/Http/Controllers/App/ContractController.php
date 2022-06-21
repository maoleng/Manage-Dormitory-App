<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\StoreContractRequest;
use App\Models\Detail;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class ContractController extends Controller
{

    #[ArrayShape(['status' => "bool", 'room_types' => "mixed", 'register_times' => "string[]"])]
    public function form(): array
    {
        $room_types = Detail::all()->toArray();
        $register_times = $this->getTimeRegister();
        return [
            'status' => true,
            'room_types' => $room_types,
            'register_times' => $register_times
        ];
    }

    public function register(StoreContractRequest $request)
    {

    }

    public function getTimeRegister(): array
    {
        $now = Carbon::now();

        $dt = Carbon::now();
        $start_ss1 = $dt->day(13)->month(9)->year($now->year-1)->toDateTimeString();
        $end_ss1 = $dt->day(23)->month(1)->year($now->year)->toDateTimeString();
        $start_ss2 = $dt->day(7)->month(2)->year($now->year)->toDateTimeString();
        $end_ss2 = $dt->day(19)->month(6)->year($now->year)->toDateTimeString();
        $start_summer = $dt->day(20)->month(6)->year($now->year)->toDateTimeString();
        $end_summer = $dt->day(7)->month(8)->year($now->year)->toDateTimeString();
        switch ($now) {
            case $now->between($end_ss1, $start_ss2):
            case $now->between($start_ss1, $end_ss1):
                return [
                    'ss2' => 'Học kì 2',
                    'summer' => 'Học kì hè'
                ];
            case $now->between($end_ss2, $start_summer):
            case $now->between($start_ss2, $end_ss2):
                return [
                    'summer' => 'Học kì hè'
                ];
            case $now->between($start_summer, $end_summer):
                return [
                    'except' => 'Đã hết đợt đăng ký'
                ];
            default:
                return [
                    'ss3' => 'Học kì năm sau'
                ];
        }
    }
}
