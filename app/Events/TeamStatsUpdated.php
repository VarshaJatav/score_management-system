<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\MatchResource;

class TeamStatsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $match;

    /**
     * Create a new event instance.
     *
     * @param \App\Http\Resources\MatchResource $match
     */
    public function __construct(MatchResource $match)
    {
        $this->match = $match;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('match.' . $this->match->id);
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'match' => $this->match,
        ];
    }

    public function broadcastAs(): string
    {
        return 'TeamStatsUpdated';
    }
}
