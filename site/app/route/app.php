<?php

use Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response;

$aApp -> get('/', function ($request, $response, $args) {
  return $response->write("Hello world");
});

$aApp -> get('/hello/{name}', function ($request, $response, $args) {
  return $response->write("Hello " . $args['name']);
});

$aApp -> get('/pokemon/all', Pokemon_Controller::Class . ':getAll');
$aApp -> get('/pokemon/combat', Pokemon_Controller::Class . ':getCombat');
$aApp -> get('/pokemon/combat/middleware', Pokemon_Controller::Class . ':getCombat') -> add(Pokemon_Middleware::Class);
$aApp -> get('/pokemon/{iIndex}', Pokemon_Controller::Class . ':getPokemon');

$aApp -> get('/phrases/get/all', Phrases_Controller::Class . ':getAll');
$aApp -> get('/phrases/get/random', Phrases_Controller::Class . ':getRandom');
$aApp -> get('/phrases/get/{iIndex}', Phrases_Controller::Class . ':getPhrase');
$aApp -> get('/phrases/count', Phrases_Controller::Class . ':count');
$aApp -> get('/phrases/add/{strPhrase}', Phrases_Controller::Class . ':addPhrase');

/****************************/
/*   Frases sin controller  */
/****************************/
/*
const FILE = '../database/phrases.txt';

$aApp -> get('/frases/get/all', function ($request, $response, $args) {
  $aFrases = file(FILE);
  foreach ($aFrases as $sFrase) {    
    echo $sFrase . '</br>'; 
  }
});

$aApp -> get('/frases/get/random', function ($request, $response, $args) {
  $aFrases = file(FILE);
  $iTotal = count($aFrases);
  $iIndex = rand(0, $iTotal - 1);
  echo $aFrases[$iIndex];
});

$aApp -> get('/frases/get/{index}', function ($request, $response, $args) {
  $aFrases = file(FILE);
  echo $aFrases[$args['index'] - 1];
});

$aApp -> get('/frases/total', function ($request, $response, $args) {
  $aFrases = file(FILE);
  echo count($aFrases);
});

$aApp -> get('/frases/post/{frase}', function ($request, $response, $args) {
  $aFrases = file(FILE);
  $iTotal = count($aFrases);
  $iIndex = $iTotal + 1;
  $sFrase = "\n" . $iIndex . '. ' . $args['frase'];
  file_put_contents(FILE, $sFrase, FILE_APPEND);
  $aFrases = file(FILE);
  foreach ($aFrases as $sFrase) {    
    echo $sFrase . '</br>'; 
  }
});
*/