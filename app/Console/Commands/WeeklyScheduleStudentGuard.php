<?php

namespace App\Console\Commands;

use App\Http\Controllers\Std\ScheduleController;
use App\Models\Attendance;
use Illuminate\Console\Command;

class WeeklyScheduleStudentGuard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:weekly_schedule_student_guard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo lịch trực mỗi tuần';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new ScheduleController)->makeSchedule();
        return 1;
    }
}
