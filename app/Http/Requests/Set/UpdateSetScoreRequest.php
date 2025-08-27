<?php

namespace App\Http\Requests\Set;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetScoreRequest extends FormRequest
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
            'sets' => 'required|array|min:1|max:5',
            'sets.*.set_number' => 'required|integer|min:1|max:5',
            'sets.*.team_a_score' => 'required|integer|min:0|max:50',
            'sets.*.team_b_score' => 'required|integer|min:0|max:50',
            'sets.*.is_completed' => 'required|boolean',
        ];
    }
}
