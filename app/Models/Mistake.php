<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mistake extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'teacher_id', 'content', 'is_confirmed', 'is_fix_mistake', 'date',
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
}

