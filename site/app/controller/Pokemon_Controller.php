<?php

namespace Controller;

const FILE = '../database/lyrics.txt';

use Psr\Container\ContainerInterface as Container,
    Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response;
    
use Model\Pokemon_Model;

class Pokemon_Controller {

  protected $cContainer;

  public function __construct (Container $cContainer) { 
    $this -> cContainer = $cContainer;  
  }

  public function getAll (Request $rRequest, Response $rResponse) {

    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] == 'json') {
      $aData = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true);
    }
    else {
      $aData = $this -> cContainer -> db -> table('pokemons') -> get();
    } 

    d($aData);
    
    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'POKEMON',
        'strDescription' => 'Lista de Pokemons'
      ],
      'aPokemons' => [
        'aData' => $aData,
        'aLyrics' => file(FILE)
      ] 
    ];

    return $this -> cContainer -> view -> render($rResponse, 'pokemons.twig', $aParameters);
  }

  public function getPokemon (Request $rRequest, Response $rResponse, $aArgs) {

    $aConfig = $this -> cContainer -> get('config');

    if ($aArgs['iIndex'] < 1 || $aArgs['iIndex'] > 151 ) return 'Error: ID no válido';
    
    if ($aConfig['db']['driver'] == 'json') {
      $aData = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true);
    }
    else {
      $aData = $this -> cContainer -> db -> table('pokemons') -> get();
    }

    d($aData[$aArgs['iIndex'] - 1]);

    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'POKEMON',
        'strDescription' => 'Un Pokemon Concreto'
      ],
      'pPokemon' => $aData[$aArgs['iIndex'] - 1]
    ];

    return $this -> cContainer -> view -> render($rResponse, 'pokemon.twig', $aParameters);
  }

  public function getCombat (Request $rRequest, Response $rResponse) {

    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] == 'json') {
      $aData = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true);
    }
    else {
      $aData = $this -> cContainer -> db -> table('pokemons') -> get();
    }

    $iNumPokemons = count($aData);

    $pPokemonUno = $aData[rand(0, $iNumPokemons - 1)];
    $pPokemonDos = $aData[rand(0, $iNumPokemons - 1)];

    if ($aConfig['db']['driver'] == 'json') {
      $attackUno = intval($pPokemonUno['baseStats']['attack']);
      $attackDos = intval($pPokemonDos['baseStats']['attack']);
    }
    else {
      $attackUno = intval($pPokemonUno -> attack);
      $attackDos = intval($pPokemonDos -> attack);
    }
    

    if ($attackUno > $attackDos) {
      $strWinner = $pPokemonUno -> name;
    }
    elseif ($attackUno < $attackDos) {
      $strWinner = $pPokemonDos -> name;
    }
    else {
      $strWinner = 'EMPATE';
    }

    // if ($attackUno > $attackDos) {
    //   $strWinner = $pPokemonUno['name'];
    // }
    // elseif ($attackUno < $attackDos) {
    //   $strWinner = $pPokemonDos['name'];
    // }
    // else {
    //   $strWinner = 'EMPATE';
    // }
    
    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'COMBATE POKEMON',
        'strDescription' => 'Combate Pokemon'
      ],
      'aPokemons' => [
        'uno' => $pPokemonUno,
        'dos' => $pPokemonDos,
        'winner' => $strWinner
      ]
    ];

    return $this -> cContainer -> view -> render($rResponse, 'pokemonCombat.twig', $aParameters);
  }

}