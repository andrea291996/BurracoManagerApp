<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IscrizioneController extends Controller{

    protected $torneoService;
    protected $squadraService;
    protected $iscrizioneService;

    public function __construct()
    {
        $this->torneoService = new TorneoService;
        $this->squadraService = new SquadraService;
        $this->iscrizioneService = new IscrizioneService;
    }
    /*
    function mostraIscritti($tipologia, $idTorneo){
        if($tipologia == "utenti"){
            $risultato = $this->torneoService->utentiIscritti($idTorneo);
        }
        if($tipologia == "circoli"){
            $risultato = $this->torneoService->circoliIscritti($idTorneo);
        }
        return $risultato;
    }

    function mostraUtentiIscritti(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Utenti Iscritti");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $idTorneo = $request->getQueryParams();
            $idTorneo = $idTorneo['idtorneo'];
            $risultato = $this->mostraIscritti("utenti", $idTorneo);
            $data = $risultato['data'];
            $template = $risultato['template'];
            $page->add("content", new IscrittiView($template, $data));
        }
        return $response;
    }

    function mostraCircoliIscritti(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Circoli Iscritti");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $idTorneo = $request->getQueryParams();
            $idTorneo = $idTorneo['idtorneo'];
            $risultato = $this->mostraIscritti("circoli", $idTorneo);
            $data = $risultato['data'];
            $template = $risultato['template'];
            $page->add("content", new IscrittiView($template, $data));
        }
        return $response;
    }

    function mostraGiocatoriSingle(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Utenti Senza Squadra");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $idTorneo = $request->getQueryParams();
            $idTorneo = $idTorneo['idtorneo'];
            $data = $this->iscrizioneService->ottieniIscrittiSingle($idTorneo);
            $template = "utenti/utentisingle";
            $page->add("content", new IscrittiView($template, $data));
        }
        return $response;
    }
    */
}

