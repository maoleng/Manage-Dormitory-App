<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mistake extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'teacher_id', 'type', 'content', 'is_confirmed', 'is_fix_mistake', 'date',
    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
        'is_fix_mistake' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'mistake_id', 'id');
    }

    public function getBeautifulTypeAttribute(): string
    {
        return match ($this->type) {
            1 => 'Không đổ rác',
            2 => 'Phòng dơ',
            3 => 'Không xếp chăn ngay ngắn',
            4 => 'Nhà vệ sinh dơ',
            5 => 'Bàn học bừa bộn',
            6 => 'Không xếp dép vào kệ',
            7 => 'Chơi game, hoạt động ồn ào quá giờ sinh hoạt',
            8 => 'Không trực bù lỗi',
            9 => 'Không xác nhận lỗi',
            default => 'Khác',
        };
    }

    public function getMistakeType(): array
    {
        return [
            [
                'number' => 1,
                'content' => 'Không đổ rác',
            ],
            [
                'number' => 2,
                'content' => 'Phòng dơ',
            ],
            [
                'number' => 3,
                'content' => 'Không xếp chăn ngay ngắn',
            ],
            [
                'number' => 4,
                'content' => 'Nhà vệ sinh dơ',
            ],
            [
                'number' => 5,
                'content' => 'Bàn học bừa bộn',
            ],
            [
                'number' => 6,
                'content' => 'Không xếp dép vào kệ',
            ],
            [
                'number' => 7,
                'content' => 'Chơi game, hoạt động ồn ào quá giờ sinh hoạt',
            ],
            [
                'number' => 8,
                'content' => 'Không trực bù lỗi',
            ],
            [
                'number' => 9,
                'content' => 'Không xác nhận lỗi',
            ],
            [
                'number' => 10,
                'content' => 'Khác',
            ],
        ];
    }

    public function getDateAttribute($date): string
    {
        return Carbon::make($date)->format('d-m-Y H:i:s');
    }
}

