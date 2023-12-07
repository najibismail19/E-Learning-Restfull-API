<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MahasiswaRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "npm" => ["required", "min:10", "max:100"],
            "nama" => ["required", "min:6", "max:100", "unique:mahasiswa"],
            "email" => ["required", "min:6","max:100", "unique:mahasiswa"],
            "password" => ["required", "min:6","max:100"],
            "kd_kelas" => ["required", "max:25"]
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response([
            "code" => 400,
            "status" => "Bad Request",
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}