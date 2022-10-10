<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonTeam extends Model
{
    use HasFactory;

    // Get the pokemon that belongs to the pokemon_team
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    // Get the team that belongs to the pokemon_team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
