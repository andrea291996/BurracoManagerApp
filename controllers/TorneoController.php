<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TorneoController extends Controller{

    protected $torneoService;

    public function __construct()
    {
        $this->torneoService = new TorneoService;
    }
    
    function mostraTuttiTornei(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Tornei");
        $user = $request->getAttribute('user');
        
        $risultato = $this->torneoService->ottieniTornei($user);
        $data = $risultato['data'];
        $template = $risultato['template'];
        $page->add("content", new HeaderView("ui/titoloeindietro.mst", ['backUrl' => "./", 'titolo' => "Tornei"]));
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
        $page->add("content", new HeaderView("ui/titoloeindietro.mst", ['backUrl' => "./", 'titolo' => "miei tornei"]));
        $page->add("content", new TorneiView($template, ['data'=>$data, 'miei' => true]));
        return $response;
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


    /*
    function infoTorneo(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Info Torneo");
        $data = $request->getQueryParams();
        $idTorneo = $data['idtorneo'];
        $risultato = $this->torneoService->infoTorneo($idTorneo);
        $data = $risultato['data'];
        $template = $risultato['template'];
        $page->add("content", new TorneiView($template, $data));
        return $response;
    }

    function mostraMieiTornei(Request $request, Response $response, $args){
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("I Miei Tornei");
        $user = $request->getAttribute('user');
        $risultato = $this->torneoService->mostraMieiTornei($user);
        $data = $risultato['data'];
        $template = $risultato['template'];
        $page->add("content", new TorneiView($template, $data));
        return $response;
    }
    */   
}

