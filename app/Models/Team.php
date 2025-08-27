<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name','short_name','logo_url','city','coach_name','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function players(): HasMany {
        return $this->hasMany(Player::class);
    }

    public function homeMatches(): HasMany {
        return $this->hasMany(MatchModel::class, 'team_a_id');
    }

    public function awayMatches(): HasMany {
        return $this->hasMany(MatchModel::class, 'team_b_id');
    }

    public function stats()
    {
        return $this->hasMany(TeamStat::class, 'team_id');
    }
}
