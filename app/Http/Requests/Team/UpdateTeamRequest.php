<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
        $id = $this->route('team')->id ?? null;
        return [
            'name' => "sometimes|string|max:255|unique:teams,name,{$id}",
            'short_name' => "sometimes|string|max:12|unique:teams,short_name,{$id}",
            'logo_url' => 'nullable|url',
            'city' => 'nullable|string|max:255',
            'coach_name' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
