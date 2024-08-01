<?php

namespace App\Http\Requests\Auth;

use App\ApiCode;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email'    => 'required|email|max:255',
            'token'    => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(ApiResponse::unprocessable(__("responses.validation.error"), $validator->errors()));
    }
}
