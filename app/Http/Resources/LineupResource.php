<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LineupResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'team_id' => $this->team_id,
            'position_number' => $this->position_number,
            'player' => new PlayerResource($this->whenLoaded('player')),
        ];
    }
}
