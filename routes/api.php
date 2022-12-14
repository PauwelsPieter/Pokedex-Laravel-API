<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('import_dump', [ImportController::class, 'import_dump']);
Route::post('import_external/{pokemon}', [ImportController::class, 'import_external']);

Route::prefix('v1')->group(function () {
    // GET    /api/v1/pokemons
    // GET    /api/v1/pokemons/{id}
    Route::resource('pokemons', PokemonController::class)->only([
        'index', 'show'
    ]);

    // GET     /api/v1/search
    Route::get('search', [PokemonController::class, 'search']);

    // GET     /api/v1/teams
    // GET     /api/v1/teams/{id}
    // POST    /api/v1/teams
    Route::resource('teams', TeamController::class)->only([
        'index', 'show', 'store'
    ]);
    // POST    /api/v1/teams/{id}
    Route::post('teams/{team}', [TeamController::class, 'set_pokemons']);
});

Route::prefix('v2')->group(function () {
    // GET    /api/v2/pokemons
    Route::get('pokemons', [PokemonController::class, 'list_paginated']);
});