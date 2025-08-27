<?php

namespace App\Http\Requests\Lineup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLineupRequest extends FormRequest
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
            'lineups' => 'required|array|min:1',
            'lineups.*.team_id' => 'required|exists:teams,id',
            'lineups.*.players' => 'required|array|size:6',
            'lineups.*.players.*.player_id' => 'required|exists:players,id',
            'lineups.*.players.*.position_number' => 'required|integer|min:1|max:6',
        ];
    }
}
