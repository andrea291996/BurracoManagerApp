<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TorneoController extends Controller{

    protected $torneoService;
    protected $utentiService;

    public function __construct()
    {
        $this->torneoService = new TorneoService;
        $this->utentiService = new UtentiService;
    }
    
    function mostraTuttiTornei(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Tornei");
        $user = $request->getAttribute('user');
        $risultato = $this->torneoService->ottieniTornei($user);
        $data = $risultato['data'];
        $template = $risultato['template'];
        $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => "./", 'titolo' => "Tornei", "isAmministratore" => $user->isAmministratore()]));
        $page->add("content", new TorneiView($template, ['data'=>$data]));
        return $response;
    }

    function mostraMieiTornei(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Miei Tornei");
        $user = $request->getAttribute('user');
        $risultato = $this->torneoService->ottieniTornei($user, true);
        $data = $risultato['data'];
        $template = $risultato['template'];
        $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => "./", 'titolo' => "miei tornei"]));
        $page->add("content", new TorneiView($template, ['data'=>$data, 'miei' => true]));
        return $response;
    }

    function mostraCreaNuovoTorneo(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Crea Nuovo Torneo");
        $user = $request->getAttribute('user');
        if($user->isAmministratore()){
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => "tornei", 'titolo' => "Crea nuovo torneo"]));
            $page->add("content", new TorneiView("tornei/creanuovotorneo.mst"));
            return $response;
        }
    }

    function mostraGiocatoriIscrittiTorneo(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Giocatori Iscritti");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams();
        $idTorneo = $idTorneo['idtorneo'];
        if($user->isAmministratore()){
            $iscritti = $this->utentiService->ottieniGiocatoriIscrittiTorneo($idTorneo);
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => 'tornei', 'titolo' => "Giocatori iscritti"]));
            $page->add("content", new IscrittiView("tornei/giocatore", ['giocatore'=> $iscritti]));
            return $response;
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
            return $response->withHeader("Location", "tornei")->withStatus(302);
        }
    }

    function mostraGiocatoriSenzaSquadra(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Giocatori Senza Squadra");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams();
        $idTorneo = $idTorneo['idtorneo'];
        if($user->isAmministratore()){
            $single = $this->utentiService->ottieniGiocatoriSenzaSquadra($idTorneo);
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => 'tornei', 'titolo' => "Giocatori senza squadra"]));
            $page->add("content", new IscrittiView("tornei/giocatore", ['giocatore'=> $single]));
            return $response;
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
            return $response->withHeader("Location", "tornei")->withStatus(302);
        }
    }

    function mostraCircoliIscrittiTorneo(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Giocatori Senza Squadra");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams();
        $idTorneo = $idTorneo['idtorneo'];
        if($user->isAmministratore()){
            $circoli = $this->utentiService->ottieniCircoliIscrittiTorneo($idTorneo);
            $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => 'tornei', 'titolo' => "Circoli iscritti"]));
            $page->add("content", new IscrittiView("tornei/circolo", ['circolo'=> $circoli]));
            return $response;
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
            return $response->withHeader("Location", "tornei")->withStatus(302);
        }
    }

    //AZIONI

    //POST
    
    public function iscrivi(Request $request, Response $response, $args){
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();
        $torneoId = $data['idtorneo'];
        $risultato = $this->torneoService->iscrivi($torneoId, $user);
        if($risultato){
            UIMessage::setSuccess(TOURNAMENT_REGISTRATION_SUCCESS);
            return $response->withHeader("Location", "./mieitornei")->withStatus(301);
        }else{
            UIMessage::setError(TOURNAMENT_REGISTRATION_FAILED);
            return $response->withHeader("Location", "./tornei")->withStatus(301);
        }
    }

    public function disiscrivi(Request $request, Response $response, $args){
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();
        $torneoId = $data['idtorneo'];
        $risultato = $this->torneoService->disiscrivi($torneoId, $user);
        if($risultato){
            UIMessage::setSuccess(TOURNAMENT_UNSUBSCRIPTION_SUCCESS);
        }else{
            UIMessage::setError(TOURNAMENT_UNSUBSCRIPTION_FAILED);
        }
        return $response->withHeader("Location", "./tornei")->withStatus(301);
    }

    public function creaNuovoTorneo(Request $request, Response $response, $args){
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();
        $nomeTorneo = $data['nometorneo'];
        if($user->isAmministratore()){
            $risultato = $this->torneoService->creaNuovoTorneo($nomeTorneo);
            if($risultato){
                UIMessage::setSuccess(TOURNAMENT_CREATION_SUCCESS);
            }else{
                UIMessage::setError(TOURNAMENT_CREATION_FAILED);
            }
        }else{
            UIMessage::setError(UNAUTHORIZED_OPERATION);
        }
        return $response->withHeader("Location", "./tornei")->withStatus(301);
    }

}

