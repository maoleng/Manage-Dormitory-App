<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'source', 'mistake_id', 'form_id', 'size'
    ];

    public function mistake(): BelongsTo
    {
        return $this->belongsTo(Mistake::class, 'mistake_id', 'id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class, 'banner_id', 'id');
    }
}
