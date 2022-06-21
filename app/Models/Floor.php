<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'building_id'
    ];

}
