<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\SaveMistakeRequest;
use App\Models\Image;
use App\Models\Mistake;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class MistakeController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function list(Request $request): array
    {
        $mistakes = Mistake::query()->orderBy('date', 'DESC');
        $mistakes = $this->filterMistake($mistakes, $request->all());
        $data = [];
        foreach ($mistakes as $mistake) {
            $data[] = [
                'id' => $mistake->id,
                'student_id' => $mistake->student->id,
                'student_card_id' => $mistake->student->student_card_id,
                'student_name' => $mistake->student->name,
                'teacher_id' => $mistake->teacher->id,
                'teacher_name' => $mistake->teacher->name,
                'type' => $mistake->beautifulType,
                'content' => $mistake->content,
                'date' => $mistake->date,
                'room_name' => $mistake->student->room->name ?? null,
                'is_confirmed' => $mistake->is_confirmed,
                'is_fix_mistake' => $mistake->is_fix_mistake,
            ];
        }

        return [
            'status' => true,
            'data' => $data
        ];
    }

    public function show($id): array
    {
        $mistake = Mistake::query()->with('student.room')->with('images')->find($id);
        if (empty($mistake)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy vi phạm'
            ];
        }
        $images = [];
        foreach ($mistake->images as $image) {
            $images[] = [
                'id' => $image->id,
                'source' => $image->source,
                'size' => $image->size,
            ];
        }

        return [
            'id' => $mistake->id,
            'student_id' => $mistake->student->id,
            'student_card_id' => $mistake->student->student_card_id,
            'student_name' => $mistake->student->name,
            'teacher_id' => $mistake->teacher->id,
            'teacher_name' => $mistake->teacher->name,
            'content' => $mistake->content,
            'type' => $mistake->beautifulType,
            'date' => $mistake->date,
            'room_name' => $mistake->student->room->name ?? null,
            'is_confirmed' => $mistake->is_confirmed,
            'is_fix_mistake' => $mistake->is_fix_mistake,
            'images' => $images
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function store(SaveMistakeRequest $request): array
    {
        checkSpam(new Mistake);
        $data = $request->validated();

        $student_id = Student::query()->where('student_card_id', $data['student_card_id'])->first()->id;
        $create = Mistake::query()->create([
            'student_id' => $student_id,
            'teacher_id' => c('teacher')->id,
            'type' => $data['type'],
            'content' => $data['content'] ?? null,
            'date' => Carbon::now(),
        ]);
        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $create_image = Image::query()->create([
                    'source' => $image,
                    'mistake_id' => $create->id,
                    'size' => size($image)
                ]);
                $images[] = [
                    'id' => $create_image->id,
                    'size' => $create_image->size . ' KB'
                ];
            }
        }

        return [
            'status' => true,
            'data' => [
                'mistake' => $create,
                'images' => $images ?? null
            ]
        ];
    }

    public function update($id, SaveMistakeRequest $request): array
    {
        $mistake = Mistake::query()->with('student.room')->with('images')->find($id);
        if (empty($mistake)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy vi phạm'
            ];
        }

        $data = $request->validated();
        $mistake->update([
            'student_card_id' => $data['student_card_id'],
            'type' => $data['type'],
            'content' => $data['content'] ?? null,
        ]);
        if (isset($data['images'])) {
            Image::query()->where('mistake_id', $mistake->id)->delete();
            foreach ($data['images'] as $image) {
                $create_image = Image::query()->create([
                    'source' => $image,
                    'mistake_id' => $mistake->id,
                    'size' => size($image)
                ]);
                $images[] = [
                    'id' => $create_image->id,
                    'size' => $create_image->size . ' KB'
                ];
            }
        }

        return [
            'status' => true,
            'data' => [
                'mistake' => Mistake::query()->find($id),
                'images' => $images ?? null
            ]
        ];
    }

    public function fixMistake($id): array
    {
        $mistake = Mistake::query()->find($id);
        if (empty($mistake)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy vi phạm'
            ];
        }
        $mistake->update(['is_fix_mistake' => true]);
        return [
            'status' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "string[]"])]
    public function mistakeType(): array
    {
        return [
            'status' => true,
            'data' => (new Mistake)->getMistakeType()
        ];
    }

    public function filterMistake($mistakes, $query_params)
    {
        $time = $query_params['time'] ?? 'this_year';
        $building_id = $query_params['building_id'] ?? null;
        $floor_id = $query_params['floor_id'] ?? null;
        $is_fix_mistake = $query_params['is_fix_mistake'] ?? null;
        $is_confirmed = $query_params['is_confirmed'] ?? null;
        if (isset($building_id)) {
            $mistakes = $mistakes
                ->whereHas('student.room.floor.building', static function ($q) use ($building_id) {
                    $q->where('id', $building_id);
                });
        }
        if (isset($floor_id)) {
            $mistakes = $mistakes
                ->whereHas('student.room.floor', static function ($q) use ($floor_id) {
                    $q->where('id', $floor_id);
                });
        }
        if (isset($is_fix_mistake)) {
            $mistakes = $mistakes->where('is_fix_mistake', $is_fix_mistake);
        }
        if (isset($is_confirmed)) {
            $mistakes = $mistakes->where('is_confirmed', $is_confirmed);
        }

        switch ($time) {
            case 'today':
                $mistakes = $mistakes->whereDate('date', now())->get();
                break;
            case 'week_ago':
                $start = now()->subWeek();
                $end = now();
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
            case 'month_ago':
                $start = now()->subMonth();
                $end = now();
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
            case 'this_year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
            default:
                $time = explode('-', $time);
                $start = Carbon::create($time[0]);
                $end = Carbon::create($time[1]);
                $mistakes = $mistakes->whereBetween('date', [$start, $end])->get();
                break;
        }

        return $mistakes;
    }
}
