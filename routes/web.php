<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/store/pokemon', ['as' => 'store.pokemon', 'uses' => 'PokemonActionsController@getAllPokemon']);

Route::get('/retrieve/pokemon', ['as' => 'retrieve.pokemon', 'uses' => 'PokemonActionsController@printPokemonInfo']);
Route::get('/store/pokemon/profiles', ['as' => 'store.pokemon.profiles', 'uses' => 'PokemonActionsController@savePokemonProfiles']);

Route::get('/find/pokemon/king', ['as' => 'find.pokemon.king', 'uses' => 'PokemonActionsController@findPokemonKing']);
