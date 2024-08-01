<?php

namespace App\Http\Requests\Teams;

use App\ApiCode;
use App\Models\Team;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateApiTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->route("team")->captain_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name'        => 'required|string|max:50',
            'description' => 'string|nullable|max:250',
            'image'       => 'url|nullable'
        ];
    }

    public function failedAuthorization() {
        throw new HttpResponseException(ApiResponse::forbidden(__("responses.teams.not_captain"), []));
        
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(ApiResponse::unprocessable(__("responses.validation.error"), $validator->errors()));
    }
}
