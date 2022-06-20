<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'student_card_id', 'password', 'role', 'information_id',
    ];
}
