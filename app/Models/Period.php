<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'period', 'started_at'
    ];

    protected $casts = [
        'started_at' => 'timestamp'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'period_id', 'id');
    }
}
