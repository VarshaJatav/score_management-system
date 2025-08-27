<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
    public function rules(): array {
        return [
            'name' => 'required|string|max:255|unique:teams,name',
            'short_name' => 'required|string|max:12|unique:teams,short_name',
            'logo_url' => 'nullable|url',
            'city' => 'nullable|string|max:255',
            'coach_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
