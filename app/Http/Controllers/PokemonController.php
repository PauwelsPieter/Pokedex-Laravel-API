<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pokemons = Pokemon::get();

        // If sorting is applied, sort the response
        if ($request->query('sort')) {
            $sort = $request->query('sort');
            $sort_column = explode('-', $sort)[0];
            $sort_direction = explode('-', $sort)[1];

            // Check the sorting parameters
            $columns = Schema::getColumnListing('pokemon');
            if (!in_array($sort_column, $columns)) {
                abort(400, "Invalid column for sorting");
            }
            if (!in_array($sort_direction, ['asc', 'desc'])) {
                abort(400, "Invalid direction for sorting");
            }

            $pokemons = Pokemon::orderBy($sort_column, $sort_direction)->get();
        }

        return $pokemons;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function show(Pokemon $pokemon)
    {
        $pokemon = Pokemon::findorfail($pokemon->id);

        return $pokemon;
    }
}