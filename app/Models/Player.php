<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = ['team_id','name','jersey_number','position','is_captain','is_active'];
    protected $casts = ['is_captain'=>'boolean','is_active'=>'boolean'];

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }

    public function stats(): HasMany {
        return $this->hasMany(PlayerStat::class);
    }
}
