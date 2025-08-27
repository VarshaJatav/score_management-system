<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'jersey_number' => 'nullable|integer',
            'position' => 'nullable|string|max:100',
            'is_captain' => 'boolean',
        ];
    }
}
