<?php

namespace App\Http\Requests\Stat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamStatsRequest extends FormRequest
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
            'team_stats' => 'required|array|min:1',
            'team_stats.*.team_id' => 'required|exists:teams,id',
            'team_stats.*.kills' => 'sometimes|integer|min:0',
            'team_stats.*.digs' => 'sometimes|integer|min:0',
            'team_stats.*.aces' => 'sometimes|integer|min:0',
            'team_stats.*.assists' => 'sometimes|integer|min:0',
            'team_stats.*.blocks' => 'sometimes|integer|min:0',
            'team_stats.*.service' => 'sometimes|integer|min:0',
        ];
    }
}
