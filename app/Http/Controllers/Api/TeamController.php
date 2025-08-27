<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    /**
     * Display a listing of active teams.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $teams = Team::with('players')->where('is_active', true)->orderBy('name')->get();
        return response()->json(TeamResource::collection($teams));
    }

    /**
     * Store a new team.
     *
     * @param StoreTeamRequest $request
     * @return JsonResponse
     */
    public function store(StoreTeamRequest $request): JsonResponse {
        $team = Team::create($request->validated());
        return response()->json(new TeamResource($team), 201);
    }

    /**
     * Display the specified team.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team): JsonResponse {
        $team->load(['players' => fn($q) => $q->where('is_active', true)->orderBy('jersey_number')]);
        return response()->json(new TeamResource($team));
    }

     /**
     * Update the specified team.
     *
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse {
        $team->update($request->validated());
        return response()->json(new TeamResource($team));
    }
}
