<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class DetailController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function index(): array
    {
        $room_details = Detail::all();
        $data = $room_details->map(function ($room_detail) {
            return [
                'id' => $room_detail->id,
                'type' => $room_detail->type,
            ];
        });
        return [
            'status' =>true,
            'data' => $data
        ];
    }
}
