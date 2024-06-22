<?php

namespace App\Http\Requests\Tournaments;

use Illuminate\Foundation\Http\FormRequest;

class StoreTournamentRequest extends FormRequest
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
            'description'           => 'string|nullable|max:250',
            'game_id'               => 'required|numeric|exists:games,id',
            'start_date'            => 'nullable|date',
            'end_date'              => 'nullable|date',
            'places'                => 'required|numeric',
            'cashprize'             => 'nullable|string',
            'image'                 => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'type'                  => 'string|in:team,solo',
            'normal_place_price'    => 'required|numeric',
            'last_week_place_price' => 'nullable|numeric'
        ];
    }
}
