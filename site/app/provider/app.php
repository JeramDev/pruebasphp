<?php

$aContainer = $aApp -> getContainer();

$aContainer['view'] = function ($cContainer) {

  $aConfig = $cContainer -> get('config')['view'];

  $vViews = new \Slim\Views\Twig($aConfig['path'], $aConfig['twig']);
  
  $vViews -> addExtension(new \Slim\Views\TwigExtension(
    $cContainer -> router,
    $cContainer -> request -> getUri()
  ));

  return $vViews;

};

$aContainer['db'] = function ($cContainer) { 

  $aConfig = $cContainer -> get('config')['db'];

  if ($aConfig['driver'] != 'mysql') return $aConfig[$aConfig['driver']];
  
  $mManager = new \Illuminate\Database\Capsule\Manager;
  $mManager -> addConnection($aConfig[$aConfig['driver']]);
  $mManager -> setAsGlobal();
  $mManager -> bootEloquent();

  return $mManager;
  
};

$aContainer['Pokemon_Controller'] = function ($cContainer) { return new \Controller\Pokemon_Controller($cContainer); };
$aContainer['Phrases_Controller'] = function ($cContainer) { return new \Controller\Phrases_Controller($cContainer); };
$aContainer['Pokemon_Middleware'] = function ($cContainer) { return new \Middleware\Pokemon_Middleware($cContainer); };