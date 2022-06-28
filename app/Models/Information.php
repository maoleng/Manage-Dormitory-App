<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Information extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'birthday', 'gender', 'birthplace', 'ethnic', 'religion', 'phone', 'identify_card', 'address', 'area',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
