<?php

namespace App\Http\Requests\Mng;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateStudentRequest extends FormRequest
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
    #[ArrayShape(['role' => "array"])]
    public function rules(): array
    {
        return [
            'role' => [
                'nullable',
                Rule::in([
                    Student::SINH_VIEN,
                    Student::TU_QUAN,
                    Student::TRUONG_PHONG,
                    Student::SINH_VIEN_TRONG_KI_TUC_XA,
                ]),
            ],
        ];
    }

    #[ArrayShape(['in' => "string"])]
    public function messages(): array
    {
        return [
            'in' => ":attribute phải nằm trong" . ", " .
                Student::SINH_VIEN . ", " .
                Student::TU_QUAN . ", " .
                Student::TRUONG_PHONG . ", " .
                Student::SINH_VIEN_TRONG_KI_TUC_XA,
        ];
    }

    #[ArrayShape(['role' => "string"])]
    public function attributes(): array
    {
        return [
            'role' => "vai trò",
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
