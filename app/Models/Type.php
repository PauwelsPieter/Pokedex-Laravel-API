<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // Get all of the pokemon_types of a pokemon
    public function pokemon_types()
    {
        return $this->hasMany(PokemonType::class);
    }
}
