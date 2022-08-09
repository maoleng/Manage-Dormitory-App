<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    public const GIOI_THIEU = 'Giới thiệu';
    public const THONG_BAO = 'Thông báo';
    public const TIN_TUC = 'Tin tức';
    public const HOAT_DONG = 'Hoạt động';
    public const HUONG_DAN = 'Hướng dẫn';
    public const NOI_QUY = 'Nội quy';

    protected $fillable = [
        'title', 'content', 'banner_id', 'category', 'teacher_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'banner_id', 'id');
    }

    public function getCategoryNameAttribute()
    {
        switch ($this->category) {
            case 1:
                return self::GIOI_THIEU;
            case 2:
                return self::THONG_BAO;
            case 3:
                return self::TIN_TUC;
            case 4:
                return self::HOAT_DONG;
            case 5:
                return self::HUONG_DAN;
            case 6:
                return self::NOI_QUY;
        }
    }

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }
}
