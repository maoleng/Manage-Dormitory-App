<?php

namespace App\Http\Requests\Mng;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class AnswerFormRequest extends FormRequest
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
    #[ArrayShape(['parent_id' => "string[]", 'content' => "string[]", 'images' => "string[]"])]
    public function rules(): array
    {
        return [
            'parent_id' => [
                'required',
                'exists:App\Models\Form,id'
            ],
            'content' => [
                'required'
            ],
            'images' => [
                'nullable'
            ]
        ];
    }

    #[ArrayShape(['required' => "string", 'exists' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
            'exists' => ':attribute không tồn tại'
        ];
    }

    #[ArrayShape(['parent_id' => "string", 'content' => "string"])]
    public function attributes(): array
    {
        return [
            'parent_id' => 'mã của đơn cần trả lời',
            'content' => 'nội dung của đơn'
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'message'   => 'Lỗi dữ liệu truyền lên',
            'data'      => $validator->errors()
        ]));
    }

}
