<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScoreboardResource extends JsonResource
{
    public function toArray($request): array {
        // $this is an array built by ScoreboardService
        return [
            'match_id' => $this['match_id'],
            'status' => $this['status'],
            'match_date' => $this['match_date'],
            'venue' => $this['venue'],
            'teams' => [
                'a' => [
                    'id' => $this['teams']['a']['id'],
                    'name' => $this['teams']['a']['name'],
                    'short_name' => $this['teams']['a']['short_name'],
                    'logo_url' => $this['teams']['a']['logo_url'],
                    'coach' => $this['teams']['a']['coach_name'],
                    'lineup' => $this['teams']['a']['lineup'], // ordered players
                    'sets_won' => $this['teams']['a']['sets_won'],
                    'totals' => $this['teams']['a']['totals'],
                ],
                'b' => [
                    'id' => $this['teams']['b']['id'],
                    'name' => $this['teams']['b']['name'],
                    'short_name' => $this['teams']['b']['short_name'],
                    'logo_url' => $this['teams']['b']['logo_url'],
                    'coach' => $this['teams']['b']['coach_name'],
                    'lineup' => $this['teams']['b']['lineup'],
                    'sets_won' => $this['teams']['b']['sets_won'],
                    'totals' => $this['teams']['b']['totals'],
                ],
            ],
            'sets' => $this['sets'], // array of {set_number, a, b, is_completed}
            'current_set' => $this['current_set'],
        ];
    }
}
