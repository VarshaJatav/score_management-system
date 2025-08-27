<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchRequest extends FormRequest
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
            'team_a_id' => 'sometimes|exists:teams,id|different:team_b_id',
            'team_b_id' => 'sometimes|exists:teams,id',
            'match_date' => 'sometimes|date',
            'venue' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:scheduled,live,completed,cancelled',
            'winner_team_id' => 'sometimes|nullable|exists:teams,id',
        ];
    }
}
