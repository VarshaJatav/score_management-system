<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SetResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'set_number' => $this->set_number,
            'team_a_score' => $this->team_a_score,
            'team_b_score' => $this->team_b_score,
            'is_completed' => (bool)$this->is_completed,
        ];
    }
}
