<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceStudent extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'attendance_id', 'student_id', 'status', 'note'
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function getRawStatusAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'Vắng';
            case 1:
                return 'Có mặt';
            case 2:
                return 'Vắng có phép';
        }
    }
}
