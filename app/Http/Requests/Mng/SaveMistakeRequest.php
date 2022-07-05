<?php

namespace App\Http\Requests\Mng;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SaveMistakeRequest extends FormRequest
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
    #[ArrayShape(['student_card_id' => "array", 'content' => "string[]", 'images' => "string[]", 'is_fix_mistake' => "string[]"])]
    public function rules(): array
    {
        return [
            'student_card_id' => [
                'required',
                Rule::exists(Student::class, 'student_card_id'),
            ],
            'content' => [
                'required'
            ],
            'images' => [
                'nullable'
            ],
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

    #[ArrayShape(['student_card_id' => "string", 'content' => "string"])]
    public function attributes(): array
    {
        return [
            'student_card_id' => "mã thẻ sinh viên",
            'content' => "nội dung vi phạm"
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
