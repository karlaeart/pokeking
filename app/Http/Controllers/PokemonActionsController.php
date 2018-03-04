<?php
/**
 * Created by PhpStorm.
 * User: salome
 * Date: 23/2/2018
 * Time: 9:41 μμ
 */

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Pokemon;
use App\PokemonProfile;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PokemonActionsController extends Controller
{
    //saves each pokemon's info from the API to the pokemon_profiles table
    public function savePokemonProfiles() {
        try {
            $pokemons = Pokemon::all();

            foreach ($pokemons as $pokemon) {
                $url = $pokemon->url;
                $client = new Client(['verify' => false]);
                $request = $client->request('GET', $url);
                $body = $request->getBody();
                $res = $body->getContents();
                $result = json_decode($res);

                if ($result->weight >= 50 && $result->sprites->front_default) {
                    try {
                        $pokemon_profile = new PokemonProfile();
                        $pokemon_profile->pokemon_id = $pokemon->id;
                        $pokemon_profile->info = $res;
                        $pokemon_profile->save();
                    } catch (\Exception $e) {}
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //sends pokemon info to the view
    public function printPokemonInfo() {
        $pokemon_profiles = PokemonProfile::all();

        foreach ($pokemon_profiles as $pokemon_profile) {
            $info = json_decode($pokemon_profile->info);
            $info_array[] = array($info);
        }
        //sort pokemon from fattest to thinnest in the array
        usort($info_array, function($a,$b){
            if ($a[0]->weight==$b[0]->weight) return 0;
            return ($a[0]->weight<$b[0]->weight)?1:-1;
        });

        //create manual paginator
        $total = count($pokemon_profiles);
        $page = Input::get('page', 1); // Get the ?page=1 from the url
        $perPage = 10; // Number of items per page
        $offset = ($page * $perPage) - $perPage;

        $itemsForCurrentPage = array_slice($info_array, $offset, $perPage, true);

        $paginator = new LengthAwarePaginator($itemsForCurrentPage, $total, $perPage, Paginator::resolveCurrentPage(), [
            'path' => Paginator::resolveCurrentPath()]);

        return view('pokemon')->with('paginator', $paginator);
    }

    public function getAllPokemon() {
        $url = 'https://pokeapi.co/api/v2/pokemon/';

        return $this->collectNextPage($url);
    }

    //Recursive function that iterates over the pages of the pokemon results and saves
    //each pokemon's name and url to the pokemons table
    public function collectNextPage($url) {
        try {
            if ($url != null) {
                $client = new Client(['verify' => false]);
                $request = $client->request('GET', $url);
                $body = $request->getBody();
                $res = $body->getContents();
                $result = json_decode($res);

                foreach ($result->results as $data) {
                    try {
                        //store data in database
                        $pokemon = new Pokemon();
                        $pokemon->name = $data->name;
                        $pokemon->url = $data->url;
                        $pokemon->save();
                    } catch (\Exception $e) {
                        //do nothing
                    }
                }
                $next_url = $result->next;
                return $this->collectNextPage($next_url);
            } else {
                $message = "All pokemon data are successfully stored in the database!";
                return $message;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}