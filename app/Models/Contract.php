<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'room_id', 'room_type', 'start_date', 'end_date', 'season', 'is_accept', 'subscription_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function getBeautifulSeasonAttribute(): string
    {
        return match ($this->season) {
            'ss1' => "Học kỳ 1",
            'ss2' => "Học kỳ 2",
            'summer' => "Học kỳ hè",
            '2ss' => "Cả 2 học kỳ",
            default => "Hết đợt đăng ký",
        };
    }

    public function getBeautifulRoomTypeAttribute(): string
    {
        return match ($this->room_type) {
            '2' => "Phòng 2 người",
            '4' => "Phòng 4 người",
            '6' => "Phòng 6 người",
            default => "Phòng 8 người",
        };
    }

    public function getContractStatusAttribute(): string
    {
        if (empty($this->room_id)) {
            return "Đăng ký thành công";
        }
        return "Đã nhận phòng";
    }
}
