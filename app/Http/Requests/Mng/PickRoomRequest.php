<?php

namespace App\Http\Requests\Mng;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class PickRoomRequest extends FormRequest
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
    #[ArrayShape(['room_id' => "string[]"])]
    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'exists:App\Models\Room,id'
            ]
        ];
    }

    #[ArrayShape(['required' => "string", 'exists' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
            'exists' =>  ":attribute không tồn tại"
        ];
    }

    #[ArrayShape(['room_id' => "string"])]
    public function attributes(): array
    {
        return [
            'room_id' => "mã phòng",
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
