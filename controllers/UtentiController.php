<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UtentiController extends Controller{

    protected $utentiService;

    public function __construct()
    {
        $this->utentiService = new UtentiService;
    }
    
    public function mostraTuttiUtentiGiocatori(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Tutti gli utenti");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $utentiGiocatori = $this->utentiService->ottieniTuttiUtentiGiocatori();
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => './', 'titolo' => "Giocatori"]));
            $page->add("content", new IscrittiView("tornei/giocatore", ['giocatore'=> $utentiGiocatori]));
            return $response;
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
            return $response->withHeader("Location", "./")->withStatus(302);
        }
    }

    public function mostraTuttiUtentiCircoli(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Tutti i circoli");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $utentiCircoli = $this->utentiService->ottieniTuttiUtentiCircoli();
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => './', 'titolo' => "Circoli"]));
            $page->add("content", new IscrittiView("tornei/circolo", ['circolo'=> $utentiCircoli]));
            return $response;
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
            return $response->withHeader("Location", "./")->withStatus(302);
        }
    }
        
}

