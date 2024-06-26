<?php

namespace App\Http\Requests\Tournaments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentRequest extends FormRequest
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
            'name'                  => 'required|string|max:50',
            'description'           => 'string|max:250',
            'game_id'               => 'required|numeric|exists:games,id',
            'start_date'            => 'nullable|date',
            'end_date'              => 'nullable|date',
            'places'                => 'required|numeric',
            'cashprize'             => 'nullable|string',
            // 'image'                 => 'nullable|image|url|mimes:png,jpg,jpeg,gif,svg|max:2048',
        ];
    }
}
