<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'period_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

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


