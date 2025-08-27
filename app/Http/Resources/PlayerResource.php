<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'jersey_number' => $this->jersey_number,
            'position' => $this->position,
            'is_captain' => (bool)$this->is_captain,
        ];
    }
}
