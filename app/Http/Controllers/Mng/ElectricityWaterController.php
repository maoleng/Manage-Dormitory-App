<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\ElectricityWater;
use App\Models\Room;
use App\Models\Subscription;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;

class ElectricityWaterController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function index(Request $request): array
    {
        $query_params = $request->all();

        $last_day = (new Carbon('last day of last month'));
        if (empty($query_params['year']) || empty($query_params['month'])) {
            $subscriptions = Subscription::query()
                ->where('type', Subscription::ELECTRICITY_WATER)
                ->whereDate('pay_start_time', $last_day)
                ->with('electricityWater')
                ->with('room.floor.building')
                ->get();
        } else {
            $subscriptions = Subscription::query()
                ->where('type', Subscription::ELECTRICITY_WATER)
                ->with('electricityWater')
                ->with('room.floor.building')
                ->get();
        }

        if (isset($query_params['building_id'])) {
            $subscriptions = $subscriptions->filter(static function ($subscription) use ($query_params) {
                return $subscription->room->floor->building->id === (int) $query_params['building_id'];
            });
        }
        if (isset($query_params['floor_id'])) {
            $subscriptions = $subscriptions->filter(static function ($subscription) use ($query_params){
                return $subscription->room->floor->id === (int)$query_params['floor_id'];
            });
        }
        if (isset($query_params['is_paid'])) {
            $subscriptions = $subscriptions->filter(static function ($subscription) use ($query_params){
                return $subscription->is_paid === (bool)$query_params['is_paid'];
            });
        }
        if (isset($query_params['year'])) {
            $subscriptions = $subscriptions->filter(static function ($subscription) use ($query_params){
                return $subscription->collectionStartTime->year === (int)$query_params['year'];
            });
        }
        if (isset($query_params['month'])) {
            $subscriptions = $subscriptions->filter(static function ($subscription) use ($query_params){
                return $subscription->collectionStartTime->month === (int)$query_params['month'];
            });
        }

        $btf_subscriptions = $subscriptions->map(static function ($subscription) {
            return [
                'subscription_id' => $subscription->id,
                'room_name' => $subscription->room->name,
                'electricity_count' => $subscription->electricityWater->electricity_count,
                'water_count' => $subscription->electricityWater->water_count,
                'price' => $subscription->price,
                'pay_end_time' => $subscription->pay_end_time,
                'is_paid' => $subscription->is_paid,
            ];
        })->toArray();

        return [
            'status' => true,
            'data' => array_values($btf_subscriptions)
        ];

    }

    public function detail($id): array
    {
        $subscription = Subscription::query()->where('type', Subscription::ELECTRICITY_WATER)->find($id);
        if (empty($subscription)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy hóa đơn'
            ];
        }

        $electricity_count = $subscription->electricityWater->electricity_count;
        $water_count = $subscription->electricityWater->water_count;
        $money_per_kwh = $subscription->electricityWater->money_per_kwh;
        $money_per_m3 = $subscription->electricityWater->money_per_m3;

        return [
            'status' => true,
            'data' => [
                'room_name' => $subscription->room->name,
                'money' => [
                    'electricity_count' => $electricity_count,
                    'water_count' => $water_count,
                    'money_per_kwh' => $money_per_kwh,
                    'money_per_m3' => $money_per_m3,
                    'electricity_money' => $electricity_count * $money_per_kwh,
                    'water_money' => $water_count * $money_per_m3,
                    'total_money' => $subscription->price,
                ],
                'pay_start_time' => $subscription->pay_start_time,
                'pay_end_time' => $subscription->pay_end_time,
            ]
        ];

    }

    #[NoReturn]
    public function getBill(): void
    {
        $faker = Faker::create();
        $last_day = (new Carbon('last day of last month'));
        $check = Subscription::query()
            ->whereDate('pay_start_time', $last_day)
            ->where('type', Subscription::ELECTRICITY_WATER)
            ->first();

        if (empty($check)) {
            $room_ids = Room::query()->where('amount', '!=', '0')
                ->get()->pluck('id')->toArray();
            foreach ($room_ids as $room_id) {
                $last_day = (new Carbon('last day of last month'));
                $subscription = Subscription::query()->create([
                    'room_id' => $room_id,
                    'type' => Subscription::ELECTRICITY_WATER,
                    'is_paid' => $faker->boolean,
                    'pay_start_time' => $last_day->toDateTimeString(),
                    'pay_end_time' => $last_day->addDays(7)->toDateTimeString(),
                ]);
                $electricity_water = ElectricityWater::query()->create([
                    'subscription_id' => $subscription->id,
                    'electricity_count' => $faker->numberBetween(100, 200),
                    'water_count' => $faker->numberBetween(1, 3),
                    'money_per_m3' => 15000,
                    'money_per_kwh' => 2500,
                ]);
                $price = $electricity_water->electricity_count * $electricity_water->money_per_kwh
                    + $electricity_water->water_count * $electricity_water->money_per_m3;
                $subscription->update([
                    'price' => $price
                ]);
            }
        }
    }
}
