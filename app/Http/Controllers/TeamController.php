<?php

namespace App\Http\Controllers;

use App\Models\PokemonTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::get();

        return $teams;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $team = Team::findorfail($team->id);

        return $team;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate $request
        $this->validate($request, [
            'name' => 'required|min:2|max:100',
        ]);

        // Create a new team
        $team = new Team();
        $team->name = $request->name;
        $team->save();

        return $team;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function set_pokemons(Request $request, Team $team)
    {
        $team = Team::findorfail($team->id);

        // Validate $request
        $this->validate($request, [
            'pokemons' => 'required|array|min:1|max:6',
            'pokemons.*' => 'required|int|distinct|exists:pokemon,id'
        ]);
        // Create new pokemon_teams
        foreach ($request->pokemons as $pokemon) {
            $pokemon_team = new PokemonTeam();
            $pokemon_team->team_id = $team->id;
            $pokemon_team->pokemon_id = $pokemon;
            $pokemon_team->save();
        }

        $pokemons_of_team = PokemonTeam::where('team_id', '=', $team->id)->pluck('pokemon_id');
        $team->pokemons = $pokemons_of_team;

        return $team;
    }
}
