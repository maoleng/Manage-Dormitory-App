<?php

namespace App\Console\Commands;

use App\Http\Controllers\Mng\ElectricityWaterController;
use Illuminate\Console\Command;

class MonthlyElectricityWaterSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:monthly_electricity_water_subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mỗi tháng tự động tạo hóa đơn điện nước cho tất cả các phòng';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new ElectricityWaterController)->getBill();
        echo 'oke';
        return 0;
    }
}
