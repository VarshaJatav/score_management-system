<?php

namespace App\Http\Controllers\Api;

use App\Models\MatchModel;
use App\Models\TeamLineup;
use App\Events\LineupUpdated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LineupResource;
use App\Http\Requests\Lineup\UpdateLineupRequest;

class TeamLineupController extends Controller
{
    /**
     * Update the lineups of both teams for a given match.
     * @param UpdateLineupRequest $request
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function updateLineup(UpdateLineupRequest $request, MatchModel $match): JsonResponse
    {
        DB::transaction(function () use ($request, $match) {
            foreach ($request->validated()['lineups'] as $teamLineup) {
                TeamLineup::where('match_id',$match->id)
                    ->where('team_id',$teamLineup['team_id'])
                    ->delete();

                foreach ($teamLineup['players'] as $p) {
                    TeamLineup::create([
                        'match_id'=>$match->id,
                        'team_id'=>$teamLineup['team_id'],
                        'player_id'=>$p['player_id'],
                        'position_number'=>$p['position_number'],
                        'is_starter'=>true,
                    ]);
                }
            }
        });

        $lineups = TeamLineup::where('match_id',$match->id)
            ->with('player')
            ->orderBy('team_id')
            ->orderBy('position_number')
            ->get();
        broadcast(new LineupUpdated(new MatchResource($match->load(['sets','teamA','teamB']))));
        return response()->json(LineupResource::collection($lineups));
    }

    /**
     * Retrieve the lineups for a given match.
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function getMatchLineups(MatchModel $match): JsonResponse
    {
        $lineups = TeamLineup::where('match_id',$match->id)
            ->with('player')
            ->orderBy('team_id')->orderBy('position_number')->get();

        return response()->json(LineupResource::collection($lineups));
    }
}
