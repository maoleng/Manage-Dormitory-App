<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'period', 'started_at'
    ];

    protected $casts = [
        'started_at' => 'timestamp'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'period_id', 'id');
    }

    public function currentSchedules(): HasMany
    {
        $now = Carbon::now();
        $start_week = $now->startOfWeek()->format('Y-m-d');
        $end_week = $now->endOfWeek()->format('Y-m-d');
        return $this->hasMany(Schedule::class, 'period_id', 'id')
            ->whereBetween('date', [$start_week, $end_week]);
    }

    public function nextSchedules(): HasMany
    {
        $next_week = Carbon::now()->next('Monday');
        $start_week = $next_week->startOfWeek()->format('Y-m-d');
        $end_week = $next_week->endOfWeek()->format('Y-m-d');
        return $this->hasMany(Schedule::class, 'period_id', 'id')
            ->whereBetween('date', [$start_week, $end_week]);
    }

    public function getPeriodDetailAttribute(): string
    {
        $started_at = Carbon::createFromTimestamp($this->started_at);
        return $started_at->format('H:i') .  ' - ' .  $started_at->addMinutes(90)->format('H:i');
    }
}
