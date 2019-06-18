<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class pokeController extends Controller
{

    public function getAllPokemon($limit = 50){
        $client = new Client([
            'base_uri' => 'http://pokeapi.co/api/v2/',
            'timeout'  => 2.0,
        ]); 
        $response = $client->request('GET','pokemon' , ['query' => 'limit=' . $limit ]);
        $response =  json_decode( $response->getBody()->getContents());
        $arrayResponse = array ();    
        foreach ($response->results as $key => $pokemon) {
           $response =  $this->getPokemon($pokemon->url);
           $response =  $this->filterResponse(json_decode($response->getBody()->getContents()));
           array_push($arrayResponse, $response); 
        }
  
        return response()->json($arrayResponse);
    }

    //Recupera un unico pokemon
    public function getPokemon($url){
        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 2.0,
        ]);

        return $client->request('GET');
        
    }

    //Funcion para crear un objeto con los datos de la respuesta.
    public function filterResponse($pokemonResponse){
        $pokemon = app();
        $pokemon = $pokemon->make('stdClass');
        $pokemon->id = $pokemonResponse->id;
        $pokemon->name = $pokemonResponse->name;
        $pokemon->types = $pokemonResponse->types;
        $pokemon->sprites = $pokemonResponse->sprites;
        return $pokemon;
    }
}
