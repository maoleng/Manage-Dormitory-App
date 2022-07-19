<?php

namespace App\Http\Requests\Mng;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class StoreTagRequest extends FormRequest
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
    #[ArrayShape(['name' => "string[]", 'color' => "string[]"])]
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'color' => [
                'nullable'
            ]
        ];
    }

    #[ArrayShape(['required' => "string"])]
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống',
        ];
    }

    #[ArrayShape(['name' => "string", 'color' => "string"])]
    public function attributes(): array
    {
        return [
            'name' => 'tên',
            'color' => 'màu'
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
