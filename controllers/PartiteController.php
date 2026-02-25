<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartiteController extends Controller{
    protected $partiteService;

    public function __construct()
    {
        $this->partiteService = new PartiteService();
    }
    
    function mostraPartiteTorneo(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Le mie partite");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams();
        $idTorneo = $idTorneo['idtorneo'];
        $partite = $this->partiteService->ottieniPartite($user, $idTorneo);
        $referer = $request->getHeaderLine('Referer');
        $backUrl = (strpos($referer, 'mieitornei') !== false) ? 'mieitornei' : 'tornei';
        if($user->isAmministratore()){
            $titolo = "tutte le partite";
        }else{
            $titolo = "le mie partite";
        }
        $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => $backUrl, 'titolo' => $titolo]));
        $page->add("content", new PartiteView("partite/partite", ['data'=>$partite]));
        return $response;
    }

    //AZIONI

    //punteggio inserito da giocatore, dovrò poi essere validato
    function inserisciMioPunteggio(Request $request, Response $response, $args){
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();
        $risultato = $this->partiteService->inserisciMioPunteggio($data, $user);
        $idTorneo = $data['idtorneo'];
        if($risultato == "TORNEO_CONCLUSO"){
            return $response->withHeader("Location", "./classifica?idtorneo=$idTorneo")->withStatus(301);
        }
        return $response->withHeader("Location", "./partite?idtorneo=$idTorneo")->withStatus(301);
    }
  
}

