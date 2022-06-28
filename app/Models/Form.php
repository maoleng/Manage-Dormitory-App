<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'type', 'content', 'is_finish', 'teacher_id', 'student_id', 'answer_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function childAnswer(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'id', 'answer_id');
    }

    public function parentAnswer(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'answer_id', 'id');
    }
}
