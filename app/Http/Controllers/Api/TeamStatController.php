<?php

namespace App\Http\Controllers\Api;

use App\Models\TeamStat;
use App\Models\MatchModel;
use App\Events\TeamStatsUpdated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MatchResource;
use App\Http\Resources\TeamStatResource;
use App\Http\Requests\Stat\UpdateTeamStatsRequest;

class TeamStatController extends Controller
{
    /**
     * Update statistics for teams in a given match.
     * @param UpdateTeamStatsRequest $request
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function updateStats(UpdateTeamStatsRequest $request, MatchModel $match): JsonResponse
    {
        DB::transaction(function () use ($request, $match) {
            foreach ($request->validated()['team_stats'] as $stat) {
                TeamStat::updateOrCreate(
                    ['match_id' => $match->id, 'team_id'=>$stat['team_id']],
                    collect($stat)->except('team_id')->toArray()
                );
            }
        });

        $stats = TeamStat::where('match_id',$match->id)->with('team')->get();
        broadcast(new TeamStatsUpdated(new MatchResource($match->load(['sets','teamA','teamB']))));
        return response()->json(TeamStatResource::collection($stats));
    }

    /**
     * Retrieve statistics for all teams in a given match.
     *
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function getMatchStats(MatchModel $match): JsonResponse
    {
        $stats = TeamStat::where('match_id', $match->id)->with('team')->get();
        return response()->json(TeamStatResource::collection($stats));
    }
}
