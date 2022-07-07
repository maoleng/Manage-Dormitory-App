<?php

namespace App\Http\Requests\Std;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class StoreFormRequest extends FormRequest
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
    #[ArrayShape(['title' => "string[]", 'content' => "string[]", 'images' => "string[]"])]
    public function rules(): array
    {
        return [
            'title' => [
                'required'
            ],
            'content' => [
                'required'
            ],
            'images' => [
                'nullable'
            ]
        ];
    }

    #[ArrayShape(['required' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
        ];
    }

    #[ArrayShape(['title' => "string", 'content' => "string"])]
    public function attributes(): array
    {
        return [
            'title' => "tiêu đề của đơn",
            'content' => "nội dung của đơn"
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
