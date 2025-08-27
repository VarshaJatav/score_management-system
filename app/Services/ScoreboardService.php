<?php

namespace App\Services;

use App\Models\MatchModel;
use App\Models\TeamStat;
use App\Models\TeamLineup;

class ScoreboardService
{
    public function build(MatchModel $match): array
    {
        // Eager load related models
        $match->load([
            'teamA.players' => fn($q) => $q->where('is_active', true)->orderBy('jersey_number'),
            'teamB.players' => fn($q) => $q->where('is_active', true)->orderBy('jersey_number'),
            'sets',
            'teamStats',
            'lineups.player'
        ]);

        // Prepare sets
        $sets = $match->sets->map(fn($s) => [
            'set_number' => $s->set_number,
            'a' => $s->team_a_score,
            'b' => $s->team_b_score,
            'is_completed' => (bool)$s->is_completed,
        ])->values();

        // Determine current active set
        $currentSetNumber = optional($match->sets->firstWhere('is_completed', false))->set_number
            ?? optional($match->sets->sortByDesc('set_number')->first())->set_number
            ?? 1;

        // Current set scores
        $currentSet = optional($match->sets->firstWhere('set_number', $currentSetNumber)) ?? null;
        $currentScores = [
            'home_score' => $currentSet?->team_a_score ?? 0,
            'away_score' => $currentSet?->team_b_score ?? 0
        ];

        // Lineups
        $lineupA = TeamLineup::where('match_id', $match->id)
            ->where('team_id', $match->team_a_id)
            ->with('player')->orderBy('position_number')->get()
            ->map(fn($l) => [
                'jersey' => $l->player->jersey_number,
                'name' => $l->player->name,
                'position_number' => $l->position_number
            ])->values();

        $lineupB = TeamLineup::where('match_id', $match->id)
            ->where('team_id', $match->team_b_id)
            ->with('player')->orderBy('position_number')->get()
            ->map(fn($l) => [
                'jersey' => $l->player->jersey_number,
                'name' => $l->player->name,
                'position_number' => $l->position_number
            ])->values();

        // Totals
        $totalsA = TeamStat::where('match_id', $match->id)
            ->where('team_id', $match->team_a_id)->first();

        $totalsB = TeamStat::where('match_id', $match->id)
            ->where('team_id', $match->team_b_id)->first();

        return [
            'match_id' => $match->id,
            'status' => $match->status,
            'match_date' => optional($match->match_date)->toDateTimeString(),
            'venue' => $match->venue,
            'teams' => [
                'a' => [
                    'id' => $match->teamA->id,
                    'name' => $match->teamA->name,
                    'short_name' => $match->teamA->short_name,
                    'logo_url' => $match->teamA->logo_url,
                    'coach_name' => $match->teamA->coach_name,
                    'lineup' => $lineupA,
                    'sets_won' => $match->team_a_sets_won,
                    'totals' => [
                        'kills' => (int)$totalsA->kills,
                        'digs' => (int)$totalsA->digs,
                        'aces' => (int)$totalsA->aces,
                        'assists' => (int)$totalsA->assists,
                        'blocks' => (int)$totalsA->blocks,
                        'service' => (int)$totalsA->service,
                    ],
                ],
                'b' => [
                    'id' => $match->teamB->id,
                    'name' => $match->teamB->name,
                    'short_name' => $match->teamB->short_name,
                    'logo_url' => $match->teamB->logo_url,
                    'coach_name' => $match->teamB->coach_name,
                    'lineup' => $lineupB,
                    'sets_won' => $match->team_b_sets_won,
                    'totals' => [
                        'kills' => (int)$totalsB->kills,
                        'digs' => (int)$totalsB->digs,
                        'aces' => (int)$totalsB->aces,
                        'assists' => (int)$totalsB->assists,
                        'blocks' => (int)$totalsB->blocks,
                        'service' => (int)$totalsB->service,
                    ],
                ],
            ],
            'sets' => $sets,
            'current_set' => $currentSetNumber,
            'current_scores' => $currentScores, // NEW
        ];
    }
}
