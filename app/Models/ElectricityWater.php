<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectricityWater extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'electricity_count', 'water_count', 'money_per_kwh', 'money_per_lit', 'subscription_id'
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }
}
