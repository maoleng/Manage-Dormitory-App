<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function showConversation($id): array
    {
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
            $data['answers'][$key]['title'] = $each_answer->title;
            $data['answers'][$key]['content'] = $each_answer->content;
            $data['answers'][$key]['created_at'] = $each_answer->created_at;
            $data['answers'][$key]['images'] = $each_answer->images;
        }

        return [
            'status' => true,
            'data' => $data
        ];

    }

    public function store(Request $request)
    {
        dd($request);
    }
}
