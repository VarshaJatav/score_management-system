<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\MatchResource;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $match;

    /**
     * @param \App\Http\Resources\MatchResource $match
     */
    public function __construct(MatchResource $match)
    {
        $this->match = $match;
    }

    public function broadcastOn()
    {
        return new Channel('match.' . $this->match->id);
    }

    public function broadcastAs()
    {
        return 'ScoreUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'match' => $this->match,
        ];
    }
}
