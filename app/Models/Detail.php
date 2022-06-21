<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Detail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'max', 'price_per_month', 'description',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany('rooms', 'detail_id', 'id');
    }
}



