<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\Pure;
use NumberFormatter;

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
        'pay_start_time' => 'datetime',
        'pay_end_time' => 'datetime',
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

    public function getDates(): array
    {
        return [
            'created_at', 'updated_at', 'pay_start_time', 'pay_end_time'
        ];
    }

    public function getCreatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getPayStartTimeAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    public function getCollectionStartTimeAttribute(): bool|Carbon
    {
        return Carbon::create($this->pay_start_time);
    }

    public function getCollectionEndTimeAttribute(): bool|Carbon
    {
        return Carbon::create($this->pay_end_time);
    }

    public function getPayEndTimeAttribute($date): string
    {
        return Carbon::create($date)->format('d-m-Y H:i:s');
    }

    #[Pure]
    public function getPriceAttribute($price): string
    {
        return (new NumberFormatter('vi_GB', NumberFormatter::CURRENCY))->formatCurrency($price, 'VND');
    }
}
