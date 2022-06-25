<?php

namespace App\Http\Requests\Mng;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class StoreContractRequest extends FormRequest
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
    #[ArrayShape(['season_time' => "array", 'room_type' => "array"])]
    public function rules(): array
    {
        return [
            'season_time' => [
                'required',
                Rule::in(['ss1', 'ss2', '2ss']),
            ],
            'room_type' => [
                'required',
                Rule::in(['2', '4', '6', '8']),
            ]
        ];
    }

    #[ArrayShape(['required' => "string", 'in' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
            'in' =>  ":attribute không hợp lệ"
        ];
    }

    #[ArrayShape(['season_time' => "string", 'room_type' => "string"])]
    public function attributes(): array
    {
        return [
            'season_time' => "thời gian đăng ký",
            'room_type' => "thể loại phòng"
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
