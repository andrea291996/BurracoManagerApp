<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SquadraController extends Controller{
    protected $squadraService;

    public function __construct()
    {
        $this->squadraService = new SquadraService();
    }

    public function mostraMiaSquadra(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("La mia squadra");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams();
        $idTorneo = $idTorneo['idtorneo'];
        $richiesteRicevute = $this->squadraService->ottieniRichiesteRicevute($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/richiestericevute", ['richiestericevute'=>$richiesteRicevute]));
        $richiesteInviate = $this->squadraService->ottieniRichiesteInviate($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/richiesteinviate", ['richiesteinviate'=>$richiesteInviate]));
        $giocatoriSingle = $this->squadraService->ottieniGiocatoriSingleNonMieiMittentiENonMieiDestinatari($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/giocatorisingle", ['giocatorisingle'=>$giocatoriSingle]));
        return $response;
    }
    /*
    function mostraMiaSquadra(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("La mia squadra");
        $user = $request->getAttribute('user');
        //CONTROLLO TIPOLOGIA UTENTE
        if($user->isGiocatore()){
            $userId = $user->getidgiocatore();
            $torneoId = $request->getQueryParams()['idtorneo'];
            $dati = $this->squadraService->ottieniDatiMiaSquadra($userId, $torneoId);
                if ($dati['squadra']) {
                    $page->add("content", new SquadreView("squadra/squadra", [
                        'squadra' => $dati['squadra'],
                        'compagno' => $dati['compagno']
                    ]));
                } else {
                    $page->add("content", new SquadreView("squadra/compagnisingle", [
                        'single' => $dati['single'],
                        'idtorneo' => $torneoId
                    ]));
                    $page->add("content", new SquadreView("squadra/richiestericevute", [
                        'mittenti' => $dati['mittenti']
                    ]));
                    $page->add("content", new SquadreView("squadra/richiesteinviate", [
                        'destinatari' => $dati['destinatari']
                    ]));
                    $page->add("content", new SquadreView("squadra/tornainlistatornei"));
                }
            }
        return $response;
    }

    function mostraTutteSquadre(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Tutte le squadre");
        $user = $request->getAttribute('user');
        //CONTROLLO TIPOLOGIA UTENTE
        if($user->isAmministratore()){
            $torneoId = $request->getQueryParams()['idtorneo'];
            $squadre = $this->squadraService->ottieniTutteSquadre($torneoId);
            $page->add("content", new SquadreView("squadra/tuttesquadre", [
            'lista_squadre' => $squadre,
            'torneo_id' => $torneoId
        ]));
        }
        return $response;
    }
    */
}

