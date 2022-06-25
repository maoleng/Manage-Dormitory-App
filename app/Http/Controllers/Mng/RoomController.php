<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class RoomController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])]
    public function all(): array
    {
        $rooms = Room::with('detail')->get();
        return [
            'status' => true,
            'data' => $rooms
        ];
    }


}
