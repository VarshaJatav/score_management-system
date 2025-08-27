<?php

namespace App\Http\Resources;

use App\Http\Resources\SetResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\LineupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'team_a' => new TeamResource($this->whenLoaded('teamA')),
            'team_b' => new TeamResource($this->whenLoaded('teamB')),
            'match_date' => optional($this->match_date)->toDateTimeString(),
            'venue' => $this->venue,
            'status' => $this->status,
            'team_a_sets_won' => $this->team_a_sets_won,
            'team_b_sets_won' => $this->team_b_sets_won,
            'winner' => new TeamResource($this->whenLoaded('winner')),
            'sets' => SetResource::collection($this->whenLoaded('sets')),
            'team_stats' => TeamStatResource::collection($this->whenLoaded('teamStats')),
            'lineups' => LineupResource::collection($this->whenLoaded('lineups')),
        ];
    }
}
