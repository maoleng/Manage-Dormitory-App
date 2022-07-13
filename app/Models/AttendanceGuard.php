<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceGuard extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'attendance_id', 'student_id', 'period', 'is_check_in', 'note',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }
}
