<?php

namespace App\Http\Controllers\Api;

use App\Models\Set;
use App\Models\MatchModel;
use App\Events\ScoreUpdated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MatchResource;
use App\Http\Requests\Match\StoreMatchRequest;
use App\Http\Requests\Match\UpdateMatchRequest;
use App\Http\Requests\Set\UpdateSetScoreRequest;

class MatchController extends Controller
{
    /**
     * Display a paginated list of matches with teams, winner, and sets.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $matches = MatchModel::with(['teamA','teamB','winner','sets'])
            ->orderByDesc('match_date')
            ->paginate(10);

        return response()->json([
            'data' => MatchResource::collection($matches->items()),
            'meta' => [
                'current_page' => $matches->currentPage(),
                'last_page' => $matches->lastPage(),
                'per_page' => $matches->perPage(),
                'total' => $matches->total(),
            ],
        ]);
    }

    /**
     * Store a newly created match and initialize 5 sets.
     *
     * @param StoreMatchRequest $request
     * @return JsonResponse
     */
    public function store(StoreMatchRequest $request): JsonResponse {
        $payload = $request->validated();

        $match = DB::transaction(function () use ($payload) {
            $m = MatchModel::create($payload + ['status' => $payload['status'] ?? 'scheduled']);
            for ($i=1; $i<=5; $i++) {
                Set::create([
                    'match_id' => $m->id,
                    'set_number' => $i,
                    'team_a_score' => 0,
                    'team_b_score' => 0,
                    'is_completed' => false,
                ]);
            }
            return $m;
        });

        $match->load(['teamA','teamB','sets']);

        return response()->json(new MatchResource($match), 201);
    }

    /**
     * Display the specified match with related data.
     *
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function show(MatchModel $match): JsonResponse {
        $match->load(['teamA.players','teamB.players','winner','sets','teamStats.team','lineups.player']);
        return response()->json(new MatchResource($match));
    }

    /**
     * Update the specified match.
     *
     * @param UpdateMatchRequest $request
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function update(UpdateMatchRequest $request, MatchModel $match): JsonResponse {
        $match->update($request->validated());
        $match->load(['teamA','teamB','winner']);
        return response()->json(new MatchResource($match));
    }

    /**
     * Update scores of sets.
     *
     * @param UpdateSetScoreRequest $request
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function updateScore(UpdateSetScoreRequest $request, MatchModel $match): JsonResponse {
        DB::transaction(function () use ($request, $match) {
            foreach ($request->validated()['sets'] as $data) {
                $winnerId = null;
                if ($data['is_completed']) {
                    $winnerId = $data['team_a_score'] > $data['team_b_score'] ? $match->team_a_id : $match->team_b_id;
                }

                Set::updateOrCreate(
                    ['match_id'=>$match->id, 'set_number'=>$data['set_number']],
                    [
                        'team_a_score'=>$data['team_a_score'],
                        'team_b_score'=>$data['team_b_score'],
                        'is_completed'=>$data['is_completed'],
                        'winner_team_id'=>$winnerId
                    ]
                );
            }

            $a = Set::where('match_id',$match->id)->where('is_completed',true)->where('winner_team_id',$match->team_a_id)->count();
            $b = Set::where('match_id',$match->id)->where('is_completed',true)->where('winner_team_id',$match->team_b_id)->count();

            $match->team_a_sets_won = $a;
            $match->team_b_sets_won = $b;
            $match->winner_team_id = $a > $b ? $match->team_a_id : ($b > $a ? $match->team_b_id : null);
            if ($a >= 3 || $b >= 3) $match->status = 'completed';
            $match->save();
        });
        broadcast(new ScoreUpdated(
            (new MatchResource($match->load(['sets','teamA','teamB','winner'])))
        ));

        $match->load(['sets','teamA','teamB']);
        return response()->json(new MatchResource($match));
    }
}
