<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'guard_id'
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id', 'id');
    }

    public function guardStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'guard_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(AttendanceStudent::class, 'attendance_id', 'id')
            ->with('student');
    }

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getDateAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }
}
