<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class BuildingController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function all(): array
    {
        return [
            'status' => true,
            'data' => Building::all(),
        ];
    }
}
