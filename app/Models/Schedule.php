<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'period', 'started_at',
    ];

    public function scheduleStudent(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'schedule_guard', 'student_id', 'schedule_id')
            ->withPivot('is_check_in');
    }
}


