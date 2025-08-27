<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'kills',
        'digs',
        'aces',
        'blocks',
        'assists',
        'service',
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
