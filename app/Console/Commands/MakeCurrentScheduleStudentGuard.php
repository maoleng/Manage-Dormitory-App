<?php

namespace App\Console\Commands;

use App\Http\Controllers\Std\ScheduleController;
use Illuminate\Console\Command;

class MakeCurrentScheduleStudentGuard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:make_current_schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo lịch trực cho tuần này (hàm dùng trong quá trình dev)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new ScheduleController)->makeCurrentSchedule();
        echo 'Tạo lịch trực cho tuần này' . '<br>';
        return 1;
    }
}
