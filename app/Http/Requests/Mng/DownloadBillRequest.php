<?php

namespace App\Http\Requests\Mng;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class DownloadBillRequest extends FormRequest
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
    #[ArrayShape(['subscription_ids' => "string[]", 'subscription_ids.*' => "string[]"])]
    public function rules(): array
    {
        return [
            'subscription_ids' => [
                'required',
                'array',
            ],
            'subscription_ids.*' => [
                'exists:App\Models\Subscription,id',
            ],
        ];
    }

    #[ArrayShape(['required' => "string", 'array' => "string", 'exists' => "string"])]
    public function messages(): array
    {
        return [
            'required' => 'Chưa nhập :attribute',
            'array' => ':attribute phải là 1 mảng',
            'exists' => ':attribute không tồn tại',
        ];
    }

    #[ArrayShape(['subscription_ids' => "string", 'subscription_ids.*' => "string"])]
    public function attributes(): array
    {
        return [
            'subscription_ids' => 'các mã hóa đơn',
            'subscription_ids.*' => 'mã hóa đơn',
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
