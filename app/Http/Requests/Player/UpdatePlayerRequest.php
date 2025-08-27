<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jersey_number' => 'nullable|integer',
            'position' => 'nullable|string|max:100',
            'is_captain' => 'boolean',
        ];
    }
}
