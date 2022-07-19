<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use HasFactory;

    public const CONTRACT = 'Hợp đồng';
    public const ELECTRICITY_WATER = 'Điện nước';

    protected $fillable = [
        'student_id', 'room_id', 'type', 'price', 'is_paid', 'pay_start_time', 'pay_end_time',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'pay_start_time' => 'timestamp',
        'pay_end_time' => 'timestamp',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function electricityWater(): HasOne
    {
        return $this->hasOne(ElectricityWater::class, 'subscription_id', 'id');
    }
}
