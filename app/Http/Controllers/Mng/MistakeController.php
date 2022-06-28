<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\StoreMistakeRequest;
use App\Models\Image;
use App\Models\Mistake;
use App\Models\Student;
use Carbon\Carbon;

class MistakeController extends Controller
{
    public function storeMistake(StoreMistakeRequest $request)
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
}
