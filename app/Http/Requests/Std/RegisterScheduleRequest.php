<?php

namespace App\Http\Requests\Std;

use App\Models\Schedule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class RegisterScheduleRequest extends FormRequest
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
    #[ArrayShape(['schedule_ids' => "string[]", 'schedule_ids.*' => "array"])]
    public function rules(): array
    {
        return [
            'schedule_ids' => [
                'required',
                'array',
            ],
            'schedule_ids.*' => [
                'required',
                Rule::exists(Schedule::class, 'id'),
            ],
        ];
    }

    #[ArrayShape(['required' => "string", 'array' => "string", 'exists' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
            'array' => ':attribute phải là 1 mảng',
            'exists' =>  ":attribute không tồn tại",
        ];
    }

    #[ArrayShape(['schedule_ids' => "string", 'schedule_ids.*' => "string"])]
    public function attributes(): array
    {
        return [
            'schedule_ids' => "mảng các mã của lịch",
            'schedule_ids.*' => "mã của lịch"
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
