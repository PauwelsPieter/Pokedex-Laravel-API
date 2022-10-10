<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    // Get all of the pokemon_teams of a pokemon
    public function pokemon_teams()
    {
        return $this->hasMany(PokemonTeam::class);
    }
}
