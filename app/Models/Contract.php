<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    public const SEASON1 = "Học kỳ 1";
    public const SEASON2 = "Học kỳ 2";
    public const SEASON_SUMMER = "Học kỳ hè";
    public const BOTH_2_SEASON = "Cả 2 học kỳ";
    public const OUT_OF_TIME = "Hết đợt đăng ký";

    protected $fillable = [
        'student_id', 'room_id', 'room_type', 'start_date', 'end_date', 'season', 'is_accept', 'subscription_id',
    ];

    protected $casts = [
        'is_accept' => 'boolean'
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
            'ss1' => $this::SEASON1,
            'ss2' => $this::SEASON2,
            'summer' => $this::SEASON_SUMMER,
            '2ss' => $this::BOTH_2_SEASON,
            default => $this::OUT_OF_TIME,
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
        if ($this->is_accept === false) {
            return "Đăng ký thành công";
        }

        if ($this->is_accept === true) {
            return "Chưa thanh toán tiền kí túc xá";
        }
        return "Đã nhận phòng";
    }
}
