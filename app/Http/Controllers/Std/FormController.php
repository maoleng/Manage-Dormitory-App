<?php

namespace App\Http\Controllers\Std;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
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

        return ((new \App\Http\Controllers\Mng\FormController)->showConversation($id));

    }

    public function store()
    {

    }
}
