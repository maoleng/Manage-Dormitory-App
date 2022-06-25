<?php

namespace App\Http\Requests\Mng;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class UpdateSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['is_paid' => "string[]"])]
    public function rules(): array
    {
        return [
            'is_paid' => [
                'boolean',
            ]
        ];
    }

    #[ArrayShape(['boolean' => "string"])]
    public function messages(): array
    {
        return [
            'boolean' => "Kiểu dữ liệu của :attribute phải là boolean",
        ];
    }

    #[ArrayShape(['is_paid' => "string"])]
    public function attributes(): array
    {
        return [
            'is_paid' => "is_paid",
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
