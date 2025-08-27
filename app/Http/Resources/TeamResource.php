<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'logo_url' => $this->logo_url,
            'city' => $this->city,
            'coach_name' => $this->coach_name,
            'players' => PlayerResource::collection($this->whenLoaded('players')),
        ];
    }
}
