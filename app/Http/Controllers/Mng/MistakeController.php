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
                'content' => $mistake->content,
                'date' => $mistake->date,
                'room_name' => $mistake->student->room->name ?? null
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
            'date' => $mistake->date,
            'room_name' => $mistake->student->room->name,
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
            'content' => $data['content'],
            'date' => Carbon::now(),
        ]);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->path();
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $create_image = Image::query()->create([
                    'source' => $base64,
                    'mistake_id' => $create->id,
                    'size' => $image->getSize()
                ]);
                $images[] = [
                    'id' => $create_image->id,
                    'size' => $create_image->size/1000 . ' KB'
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
            'content' => $data['content'],
        ]);
        if (isset($data['images'])) {
            Image::query()->where('mistake_id', $mistake->id)->delete();
            foreach ($data['images'] as $image) {
                $path = $image->path();
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $create_image = Image::query()->create([
                    'source' => $base64,
                    'mistake_id' => $mistake->id,
                    'size' => $image->getSize()
                ]);
                $images[] = [
                    'id' => $create_image->id,
                    'size' => $create_image->size/1000 . ' KB'
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

}
