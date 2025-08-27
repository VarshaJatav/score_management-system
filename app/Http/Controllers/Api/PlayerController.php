<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * List all players with their teams.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return Player::with('team')->get();
    }

    /**
     * Display the specified player with team details.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return Player::with('team')->findOrFail($id);
    }

    /**
     * Store a new player.
     *
     * @param StorePlayerRequest $request
     * @return JsonResponse
     */
    public function store(StorePlayerRequest $request)
    {
        $player = Player::create($request->validated());
        return response()->json($player, 201);
    }

     /**
     * Update the specified player.
     *
     * @param UpdatePlayerRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePlayerRequest $request, $id)
    {
        $player = Player::findOrFail($id);
        $player->update($request->validated());

        return response()->json($player);
    }

    /**
     * Remove the specified player.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json(['message' => 'Player removed successfully']);
    }
}
