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

class PokemonActionsController extends Controller
{
    public function getAllPokemon() {

        $url = 'https://pokeapi.co/api/v2/pokemon/';
        return $this->collectNextPage($url);
    }

    //Recursive function that iterates over the pages of the pokemon results
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
//                        return $e->getMessage();
                    }
                }
                $next_url = $result->next;
//                $next_url = null;
                return $this->collectNextPage($next_url);
            } else {
                $message = "All pokemon data are successfully stored in the database!";
                //die('Hi!!');
                return $message;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}