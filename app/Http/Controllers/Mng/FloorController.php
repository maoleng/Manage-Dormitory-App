<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class FloorController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function all(Request $request): array
    {
        $building_id = $request->building_id;
        if ($building_id) {
            $floors = Floor::query()->where('building_id', $building_id)->get();
        } else {
            $floors = Floor::all();
        }
        return [
            'status' => true,
            'data' => $floors
        ];
    }
}
