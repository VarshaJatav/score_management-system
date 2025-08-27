<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamStatResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'team' => new TeamResource($this->whenLoaded('team')),
            'kills' => $this->kills,
            'digs' => $this->digs,
            'aces' => $this->aces,
            'assists' => $this->assists,
            'blocks' => $this->blocks,
            'service' => $this->service,
        ];
    }
}
