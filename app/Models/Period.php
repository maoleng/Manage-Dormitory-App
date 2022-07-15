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

    public function getPeriodDetailAttribute(): string
    {
        $started_at = Carbon::createFromTimestamp($this->started_at);
        return $started_at->toTimeString() .  ' - ' .  $started_at->addMinutes(90)->toTimeString();
    }
}
