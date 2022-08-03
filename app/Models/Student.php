<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;
    public const TU_QUAN = "Sinh viên tự quản";
    public const SINH_VIEN = "Sinh viên";
    public const TRUONG_PHONG = "Trưởng phòng";
    public const SINH_VIEN_TRONG_KI_TUC_XA = "Sinh viên trong kí túc xá";

    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'student_card_id', 'password', 'role', 'room_id', 'information_id',
    ];

    protected $hidden = [
        'password'
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'student_id', 'id');
    }

    public function information(): BelongsTo
    {
        return $this->belongsTo(Information::class, 'information_id', 'id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'student_id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'student_id', 'id');
    }

    public function mistakes(): HasMany
    {
        return $this->hasMany(Mistake::class, 'student_id', 'id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'student_id', 'id');
    }

    public function scheduleStudent(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'schedule_guard', 'student_id', 'schedule_id')
            ->withPivot('is_check_in');
    }

    public function attendanceStudents(): HasMany
    {
        return $this->hasMany(AttendanceStudent::class, 'student_id', 'id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'guard_id', 'id');
    }

    protected function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function verify($password): bool
    {
        return password_verify($password, $this->password);
    }
}
