<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchModel extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'team_a_id','team_b_id','match_date','venue','status',
        'winner_team_id','team_a_sets_won','team_b_sets_won'
    ];

    protected $casts = ['match_date'=>'datetime'];

    public function teamA(): BelongsTo { return $this->belongsTo(Team::class, 'team_a_id'); }
    public function teamB(): BelongsTo { return $this->belongsTo(Team::class, 'team_b_id'); }
    public function winner(): BelongsTo { return $this->belongsTo(Team::class, 'winner_team_id'); }

    public function sets(): HasMany { return $this->hasMany(Set::class, 'match_id')->orderBy('set_number'); }
    public function teamStats(): HasMany { return $this->hasMany(TeamStat::class, 'match_id'); }
    public function lineups(): HasMany { return $this->hasMany(TeamLineup::class, 'match_id'); }

    public function isLive(): bool { return $this->status === 'live'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
}
