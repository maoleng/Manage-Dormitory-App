<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class RoomController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])]
    public function all(Request $request): array
    {
        $query_params = $request->all();

        $rooms = Room::with('detail')->get();
        if (isset($query_params['building_id'])) {
            $rooms = $rooms->filter(static function ($room) use ($query_params) {
                return $room->floor->building->id === (int) $query_params['building_id'];
            });
        }
        if (isset($query_params['floor_id'])) {
            $rooms = $rooms->filter(static function ($room) use ($query_params) {
                return $room->floor->id === (int)$query_params['floor_id'];
            });
        }
        if (isset($query_params['detail_id'])) {
            $rooms = $rooms->filter(static function ($room) use ($query_params) {
                return $room->detail_id === (int)$query_params['detail_id'];
            });
        }
        if (isset($query_params['status'])) {
            $rooms = $rooms->filter(static function ($room) use ($query_params){
                if ($query_params['status'] === 'con_trong_cho') {
                    return $room->status === 'Còn trống chỗ';
                }
                if ($query_params['status'] === 'da_het_cho') {
                    return $room->status === 'Đã hết chỗ';
                }
            });
        }

        return [
            'status' => true,
            'data' => array_values($rooms->toArray())
        ];
    }


}
