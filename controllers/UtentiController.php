<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UtentiController extends Controller{

    protected $utentiService;

    public function __construct()
    {
        $this->utentiService = new UtentiService;
    }
    
    function mostraTuttiGiocatori(Request $request, Response $response, $args) {  
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Utenti");  
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $utenti = $this->utentiService->mostraTuttiGiocatori();
            $page->add("content", new IscrittiView("utenti/tuttiutenti", $utenti));
        }
        return $response;
    }
        
}

