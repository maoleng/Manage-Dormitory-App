<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'date', 'student_id'
    ];

    public function attendanceGuard(): HasMany
    {
        return $this->hasMany(AttendanceGuard::class, 'attendance_id', 'id');
    }
}
