<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'device_id', 'token', 'teacher_id', 'student_id', 'last_login',
    ];
}
