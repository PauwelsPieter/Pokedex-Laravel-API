<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonType extends Model
{
    use HasFactory;

    // Get the pokemon that belongs to the pokemon_type
    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    // Get the type that belongs to the pokemon_type
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
