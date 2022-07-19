<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\ElectricityWater;
use App\Models\Room;
use App\Models\Subscription;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class ElectricityWaterController extends Controller
{
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
