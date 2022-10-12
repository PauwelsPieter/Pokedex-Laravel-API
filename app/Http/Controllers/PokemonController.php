<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\PokemonType;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'sort' => ['nullable', Rule::in(['name-asc', 'name-desc', 'id-asc', 'id-desc'])]
        ]);

        $pokemons = Pokemon::get();

        // If sorting is applied, sort the response
        if ($request->query('sort')) {
            $sort = $request->query('sort');
            $sort_column = explode('-', $sort)[0];
            $sort_direction = explode('-', $sort)[1];

            $pokemons = Pokemon::orderBy($sort_column, $sort_direction)->get();
        }

        // Append array of types of every pokemon
        foreach ($pokemons as $pokemon) {
            $types = PokemonType::with('type')->where('pokemon_id', '=', $pokemon->id)->get()->pluck('type.name');
            $pokemon->types = $types;
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

        // Append array of types to pokemon
        $types = PokemonType::with('type')->where('pokemon_id', '=', $pokemon->id)->get()->pluck('type.name');
        $pokemon->types = $types;

        return $pokemon;
    }


    /**
     * Display a listing of the resource including filtering and limiting.
     *
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // Validate $request
        $this->validate($request, [
            'query' => 'required',
            'limit' => 'nullable|integer'
        ]);

        $query = $request->query('query');
        $limit = $request->query('limit');

        $pokemons = Pokemon::get();
        $pokemons_filtered = [];

        // Append array of types of every pokemon
        foreach ($pokemons as $pokemon) {
            $types = PokemonType::with('type')->where('pokemon_id', '=', $pokemon->id)->get()->pluck('type.name')->toArray();
            $pokemon->types = $types;

            // Append pokemon to array if name or type matches
            if ($pokemon->name == $query || in_array($query, $types)) {
                array_push($pokemons_filtered, $pokemon);
            }
        }

        // If limit query exists, apply a limit
        if ($limit) {
            $pokemons_filtered = array_slice($pokemons_filtered, 0, $limit);
        }
        
        return $pokemons_filtered;
    }

    /**
     * Display a listing of the resource including pagination.
     *
     * @param  \App\Models\Pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function list_paginated(Request $request) 
    {
        // Validate $request
        $this->validate($request, [
            'sort' => ['nullable', Rule::in(['name-asc', 'name-desc', 'id-asc', 'id-desc'])],
            'limit' => 'nullable|integer'
        ]);

        $sort = $request->query('sort');
        $limit = $request->query('limit') ?? 10;

        $pokemons = Pokemon::paginate($limit);

        // If sorting is applied, sort the response
        if ($request->query('sort')) {
            $sort_column = explode('-', $sort)[0];
            $sort_direction = explode('-', $sort)[1];

            $pokemons = Pokemon::orderBy($sort_column, $sort_direction)->paginate($limit);
        }

        // Append array of types of every pokemon
        foreach ($pokemons as $pokemon) {
            $types = PokemonType::with('type')->where('pokemon_id', '=', $pokemon->id)->get()->pluck('type.name');
            $pokemon->types = $types;
        }

        return $pokemons;
    }
}
