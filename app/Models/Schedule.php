<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'period', 'started_at',
    ];

    protected $casts = [
        'date' => 'date',
        'started_at' => 'datetime'
    ];

    public function scheduleStudent(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'schedule_guard', 'schedule_id', 'student_id')
            ->withPivot('is_check_in');
    }

    public function getPeriodDetailAttribute(): string
    {
        $started_at = Carbon::create($this->started_at);
        return $started_at->toTimeString() .  ' - ' .  $started_at->addMinutes(90)->toTimeString();
    }
}


