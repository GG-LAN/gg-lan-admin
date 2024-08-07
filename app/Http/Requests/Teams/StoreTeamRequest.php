<?php

namespace App\Http\Requests\Teams;

use App\ApiCode;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTeamRequest extends FormRequest
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
            'name'          => 'required|string|max:50|unique:teams',
            'description'   => 'string|nullable|max:250',
            'tournament_id' => 'required|numeric|exists:tournaments,id',
            'image'         => 'url|nullable'
        ];
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(ApiResponse::unprocessable(__("responses.validation.error"), $validator->errors()));
    }
}
