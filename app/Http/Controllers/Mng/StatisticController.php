<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Mistake;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class StatisticController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function mistakeReason(Request $request): array
    {
        $query_params = $request->all();

        $total_mistake = Mistake::query();
        $chart = Mistake::query()->addSelect('type')
            ->addSelect(DB::raw('count(*) as count'))
            ->groupBy('type');
        $data = $this->filterMistake($total_mistake, $chart, $query_params);
        $total_mistake = $data['total_mistake'];
        $chart = $data['chart']->map(static function($item) use ($total_mistake) {
            return [
                'type' => $item->type,
                'type_name' => $item->beautifulType,
                'count' => $item->count,
                'percent' => round($item->count / $total_mistake, 2) * 100
            ];
        });

        return [
            'status' => true,
            'data' => [
                'title' => $data['title'],
                'total_mistake' => $total_mistake,
                'chart' => $chart
            ]
        ];


    }

    #[ArrayShape(['title' => "string", 'total_mistake' => "mixed", 'chart' => "mixed"])]
    private function filterMistake($total_mistake, $chart, $query_params): array
    {
        Carbon::setLocale('vi');
        $time = $query_params['time'] ?? 'this_year';
        $building_id = $query_params['building_id'] ?? null;
        $floor_id = $query_params['floor_id'] ?? null;
        $is_fix_mistake = $query_params['is_fix_mistake'] ?? null;
        $is_confirmed = $query_params['is_confirmed'] ?? null;
        if (isset($building_id)) {
            $chart = $chart
                ->whereHas('student.room.floor.building', static function ($q) use ($building_id) {
                    $q->where('id', $building_id);
                });
            $total_mistake = $total_mistake
                ->whereHas('student.room.floor.building', static function ($q) use ($building_id) {
                    $q->where('id', $building_id);
                });
        }
        if (isset($floor_id)) {
            $chart = $chart
                ->whereHas('student.room.floor', static function ($q) use ($floor_id) {
                    $q->where('id', $floor_id);
                });
            $total_mistake = $total_mistake
                ->whereHas('student.room.floor', static function ($q) use ($floor_id) {
                    $q->where('id', $floor_id);
                });
        }
        if (isset($is_fix_mistake)) {
            $chart = $chart->where('is_fix_mistake', $is_fix_mistake);
            $total_mistake = $total_mistake->where('is_fix_mistake', $is_fix_mistake);
        }
        if (isset($is_confirmed)) {
            $chart = $chart->where('is_confirmed', $is_confirmed);
            $total_mistake = $total_mistake->where('is_confirmed', $is_confirmed);
        }

        switch ($time) {
            case 'today':
                $start = 'hôm nay';
                $end = null;
                $total_mistake = $total_mistake->whereDate('date', now())->count();
                $chart = $chart->whereDate('date', now())->get();
                break;
            case 'week_ago':
                $start = now()->subWeek();
                $end = now();
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
            case 'month_ago':
                $start = now()->subMonth();
                $end = now();
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
            default:
                $time = explode('-', $time);
                $start = Carbon::create($time[0]);
                $end = Carbon::create($time[1]);
                $total_mistake = $total_mistake->whereBetween('date', [$start, $end])->count();
                $chart = $chart->whereBetween('date', [$start, $end])->get();
                break;
        }
        if (empty($end)) {
            $time_title = 'trong ' . $start . ':';
        } else {
            $time_title = 'từ '. $start->diffForHumans() . ' đến hiện tại';
        }

        return [
            'title' => 'Thống kê các vi phạm ' . $time_title,
            'total_mistake' => $total_mistake,
            'chart' => $chart,
        ];
    }
}
