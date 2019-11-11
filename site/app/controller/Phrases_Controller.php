<?php

namespace Controller;

use Psr\Container\ContainerInterface as Container,

    Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response;

class Phrases_Controller {

  protected $cContainer;
  private $fFile;
  private $strFileName;

  public function __construct (Container $cContainer) {
    $this -> cContainer = $cContainer;
    $this -> strFileName = $this -> cContainer -> db['filename'];
    $this -> fFile = file($this -> cContainer -> db['path'] . '/' . $this -> strFileName);
  }

  public function getAll (Request $rRequest, Response $rResponse) {
    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] != 'txt') return 'Error: Not TXT driver';

    /*$strFileContent = '';
    foreach ($this -> fFile as $strFrase) {    
      $strFileContent .= $strFrase . '</br>'; 
    }
    return $strFileContent;*/

    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'Frases',
        'strDescription' => 'Tus frases favoritas'
      ],
      'aPhrases' => $this -> fFile
    ];
    return $this -> cContainer -> view -> render($rResponse, 'phrases.twig', $aParameters);
  }

  public function getPhrase (Request $rRequest, Response $rResponse, $aArgs) {
    $aConfig = $this -> cContainer -> get('config');

    /*if ($aConfig['db']['driver'] != 'txt') return 'Error: Not TXT driver';
    
    return $this -> fFile[$aArgs['iIndex'] - 1];*/

    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'Solo una frase',
        'strDescription' => 'La frase que necesitabas'
      ],
      'strPhrase' => $this -> fFile[$aArgs['iIndex'] - 1]
    ];
    return $this -> cContainer -> view -> render($rResponse, 'phrase.twig', $aParameters);
  }

  public function count (Request $rRequest, Response $rResponse) {
    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] != 'txt') return 'Error: Not TXT driver';
    
    return strval(count($this -> fFile));
  }

  public function getRandom (Request $rRequest, Response $rResponse) {
    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] != 'txt') return 'Error: Not TXT driver';

    $iIndex = rand(0, count($this -> fFile));
    // return $this -> fFile[$iIndex];

    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'Solo una frase',
        'strDescription' => 'La frase que necesitabas'
      ],
      'strPhrase' => $this -> fFile[$iIndex]
    ];
    return $this -> cContainer -> view -> render($rResponse, 'phrase.twig', $aParameters);
  }

  public function addPhrase (Request $rRequest, Response $rResponse, $aArgs) {
    $aConfig = $this -> cContainer -> get('config');

    if ($aConfig['db']['driver'] != 'txt') return 'Error: Not TXT driver';

    $iTotal = count($this -> fFile);
    $iIndex = $iTotal + 1;
    $strFinalPhrase = "\n" . $iIndex . '. ' . $aArgs['strPhrase'];

    if(file_put_contents($this -> cContainer -> db['path'] . '/' . $this -> strFileName, $strFinalPhrase, FILE_APPEND))
      return 'Éxito al añadir una frase.';
    return 'Error al añadir una frase.';
  }
}