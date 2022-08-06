<?php

namespace App\Models;

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
}
