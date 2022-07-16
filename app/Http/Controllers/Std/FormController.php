<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\AnswerFormRequest;
use App\Http\Requests\Std\StoreFormRequest;
use App\Models\Form;
use App\Models\Image;
use JetBrains\PhpStorm\ArrayShape;

class FormController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed"])]
    public function all(): array
    {
        return [
            'status' => true,
            'data' => Form::query()
                ->where('student_id', c('student')->id)
                ->whereNull('parent_id')
                ->withCount('childAnswer')
                ->get()
        ];
    }

    public function showConversation($id): array
    {
        $form_ids = Form::query()
            ->where('student_id', c('student')->id)
            ->whereNull('parent_id')
            ->pluck('id')->toArray();
        if ( !(in_array((int)$id, $form_ids, true)) ) {
            return [
                'status' => false,
                'message' => 'Không sở hữu đơn này'
            ];
        }

        $form = Form::query()
            ->with('student')
            ->with('images')
            ->with('childAnswer.student')
            ->with('childAnswer.teacher')
            ->with('childAnswer.images')
            ->find($id);
        if (empty($form)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy đơn'
            ];
        }

        $data = [];
        $data['id'] = $form->id;
        $data['student'] = $form->student;
        $data['title'] = $form->title;
        $data['content'] = $form->content;
        $data['created_at'] = $form->created_at;
        $data['images'] = $form->images;

        foreach ($form->childAnswer as $key => $each_answer) {
            $data['answers'][$key]['id'] = $each_answer->id;
            $data['answers'][$key]['student'] = $each_answer->student;
            $data['answers'][$key]['teacher'] = $each_answer->teacher;
            $data['answers'][$key]['content'] = $each_answer->content;
            $data['answers'][$key]['created_at'] = $each_answer->created_at;
            $data['answers'][$key]['images'] = $each_answer->images;
        }

        return [
            'status' => true,
            'data' => $data
        ];

    }

    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function store(StoreFormRequest $request): array
    {
        $data = $request->validated();

        $form = Form::query()->create([
            'student_id' => c('student')->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $create_image = Image::query()->create([
                    'source' => $image,
                    'form_id' => $form->id,
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
                'form' => $form,
                'images' => $images ?? null
            ]
        ];
    }

    #[ArrayShape(['status' => "bool", 'data' => "array"])]
    public function answer(AnswerFormRequest $request): array
    {
        $data = $request->validated();
        $form = Form::query()->create([
            'student_id' => c('student')->id,
            'parent_id' => $data['parent_id'],
            'content' => $data['content'],
        ]);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $create_image = Image::query()->create([
                    'source' => $image,
                    'form_id' => $form->id,
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
                'form' => $form,
                'images' => $images ?? null
            ]
        ];

    }

}
