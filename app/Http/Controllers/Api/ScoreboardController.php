<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScoreboardResource;
use App\Models\MatchModel;
use App\Services\ScoreboardService;
use Illuminate\Http\JsonResponse;

class ScoreboardController extends Controller
{
    public function __construct(private ScoreboardService $service) {}

    /**
     * Display the scoreboard for a given match.
     *
     * @param MatchModel $match
     * @return JsonResponse
     */
    public function show(MatchModel $match): JsonResponse
    {
        $data = $this->service->build($match);
        return response()->json($data);
    }
}
