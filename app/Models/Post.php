<?php

namespace App\Models;

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
}
