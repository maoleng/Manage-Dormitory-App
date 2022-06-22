<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'type', 'room_type', 'amount', 'status', 'lead_id', 'floor_id', 'detail_id'
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'lead_id', 'id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'room_id', 'id');
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(Detail::class, 'detail_id', 'id');
    }
}

