<?php
namespace App\Http\Requests\Teams;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateApiTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route("team")->captain_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|max:50',
            'description' => 'string|nullable|max:250',
            'image'       => 'url|nullable',
        ];
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(ApiResponse::forbidden(__("responses.team.not_captain"), []));

    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(ApiResponse::unprocessable(__("responses.validation.error"), $validator->errors()));
    }
}
