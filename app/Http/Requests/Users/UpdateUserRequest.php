<?php

namespace App\Http\Requests\Users;

use App\ApiCode;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->route("player")->id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'pseudo' => 'required|string|max:255',
            'birth_date' => 'date_format:Y-m-d',
        ];
    }

    public function failedAuthorization() {
        throw new HttpResponseException(ApiResponse::forbidden(__("responses.users.cant_update"), []));
        
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(ApiResponse::unprocessable(__("responses.validation.error"), $validator->errors()));
    }
}
