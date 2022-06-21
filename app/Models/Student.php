<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'student_card_id', 'password', 'role', 'information_id',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'student_id', 'id');
    }

    public function information(): BelongsTo
    {
        return $this->belongsTo(Information::class, 'information_id', 'id');
    }
}
