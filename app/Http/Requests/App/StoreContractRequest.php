<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'student_id' => [
                'required',
                Rule::exists('students', 'student_card_id'),

            ]
        ];
    }

    public function messages()
    {
        return [
            'exists' => ":attribute Bắt buộc phải điền"
        ];
    }

    public function attributes()
    {
        return [
            'name' => "Tên đầu tiên",
        ];
    }
}
