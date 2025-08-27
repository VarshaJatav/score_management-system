<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TeamStatController;
use App\Http\Controllers\Api\ScoreboardController;
use App\Http\Controllers\Api\TeamLineupController;

Route::get('/health', fn() => response()->json(['ok'=>true]));

Route::get('scoreboard/{match}', [ScoreboardController::class, 'show']);
Route::get('matches/{match}', [MatchController::class, 'show']);
Route::get('teams', [TeamController::class, 'index']);
Route::get('teams/{team}', [TeamController::class, 'show']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::patch('matches/{match}/score', [MatchController::class, 'updateScore']);
    Route::apiResource('matches', MatchController::class)->only(['index','store','update']);

    Route::post('teams', [TeamController::class, 'store']);
    Route::patch('teams/{team}', [TeamController::class, 'update']);

    Route::patch('matches/{match}/stats', [TeamStatController::class, 'updateStats']);
    Route::get('matches/{match}/stats', [TeamStatController::class, 'getMatchStats']);

    Route::patch('matches/{match}/lineups', [TeamLineupController::class, 'updateLineup']);
    Route::get('matches/{match}/lineups', [TeamLineupController::class, 'getMatchLineups']);

    Route::apiResource('players', PlayerController::class);
});
