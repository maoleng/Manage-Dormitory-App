<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Pure;

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

    #[Pure]
    public function getTotalMoney($season): float
    {
        $month = $this->getMonth($season);
        return round($this->price_per_month * $month);
    }

    public function getMonth($season): int
    {
        return match ($season) {
            'ss1', 'ss2' => 3,
            '2ss' => 6,
            default => 2,
        };
    }
}
