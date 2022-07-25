<?php

namespace App\Http\Requests\Std;

use App\Models\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class CheckAttendanceRequest extends FormRequest
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
    #[ArrayShape(['1' => "string[]", '*.student_id' => "array", '*.status' => "array", '*.note' => "string[]"])]
    public function rules(): array
    {
        return [
            '1' => [
                'required',
                'array'
            ],
            '*.student_id' => [
                'required',
                Rule::exists(Student::class, 'id'),
            ],
            '*.status' => [
                'required',
                Rule::in(['0', '1', '2']),
            ],
            '*.note' => [
                'nullable'
            ],
        ];
    }

    #[ArrayShape(['required' => "string", 'array' => "string", 'in' => "string", 'exist' => "string"])]
    public function messages(): array
    {
        return [
            'required' => "Chưa nhập :attribute",
            'array' => ":attribute phải là 1 mảng",
            'in' =>  ":attribute không hợp lệ",
            'exist' =>  ":attribute không tồn tại",
        ];
    }

    #[ArrayShape(['0' => "string", '*.student_id' => "string", '*.status' => "string", '*.note' => "string"])]
    public function attributes(): array
    {
        return [
            '0' => "mảng các dữ liệu điểm danh từng học sinh",
            '*.student_id' => "mã học sinh",
            '*.status' => "trạng thái",
            '*.note' => "ghi chú",
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
