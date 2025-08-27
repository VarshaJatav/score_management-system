<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Set extends Model
{
    protected $table = 'sets';

    protected $fillable = ['match_id','set_number','team_a_score','team_b_score','winner_team_id','is_completed'];
    protected $casts = ['is_completed'=>'boolean'];

    public function match(): BelongsTo { return $this->belongsTo(MatchModel::class, 'match_id'); }
    public function winner(): BelongsTo { return $this->belongsTo(Team::class, 'winner_team_id'); }
}
