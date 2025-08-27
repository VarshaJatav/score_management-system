<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchRequest extends FormRequest
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
            'team_a_id' => 'required|exists:teams,id|different:team_b_id',
            'team_b_id' => 'required|exists:teams,id',
            'match_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'status' => 'nullable|in:scheduled,live,completed,cancelled',
        ];
    }
}
