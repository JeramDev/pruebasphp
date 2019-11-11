<?php

namespace Middleware;

use Psr\Container\ContainerInterface as Container,
    Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response;

class Pokemon_Middleware {

  protected $cContainer;

  public function __construct (Container $cContainer) { $this -> cContainer = $cContainer; }

  public function __invoke (Request $rRequest, Response $rResponse, $cNext) {

    $strDriverName = $this -> cContainer -> get('config')['db']['driver'];
    $aDriver = $this -> cContainer -> get('config')['db'][$strDriverName];

    $rResponse -> getBody() -> write('File is: ' . $aDriver['filename'] . ' ');
    
    $rResponse = $cNext($rRequest, $rResponse);

    return $rResponse;
  }
}