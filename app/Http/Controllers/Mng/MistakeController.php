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
        if ($request->get('time') !== null) {
            $mistakes = Mistake::query()
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->with('student.room')
                ->get();
        } else {
            $mistakes = Mistake::query()->with('student.room')->get();
        }
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
}
