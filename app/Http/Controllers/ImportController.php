<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\PokemonType;
use App\Models\Type;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import_dump(Request $request)
    {
        // Validate $request
        $this->validate($request, [
            'pokemons' => 'required|array'
        ]);
        
        // Create each Pokemon
        foreach ($request->pokemons as $pokemon) {

            $new_pokemon = new Pokemon();
            $new_pokemon->name = $pokemon['name'];
            $new_pokemon->base_experience = $pokemon['base_experience'];
            $new_pokemon->weight = $pokemon['weight'];
            $new_pokemon->save();

            // Add types that aren't added yet
            foreach ($pokemon['types'] as $type) {
                $type_name = $type['type']['name'];
                $exists = Type::where('name', '=', $type_name)->count();

                if ($exists == 0) {
                    // Create Type
                    $type = new Type();
                    $type->name = $type_name;
                    $type->save();
                }

                // Create PokemonType
                $type_id = Type::where('name', '=', $type_name)->first()->id;

                $pokemon_type = new PokemonType();
                $pokemon_type->pokemon_id = $new_pokemon->id;
                $pokemon_type->type_id = $type_id;
                $pokemon_type->save();
            }
        }
    }
}
