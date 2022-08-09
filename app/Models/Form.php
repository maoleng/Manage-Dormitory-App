<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'teacher_id', 'student_id', 'parent_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function childAnswer(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    public function parentAnswer(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'form_id', 'id');
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
