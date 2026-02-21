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
        $referer = $request->getHeaderLine('Referer');
        $backUrl = (strpos($referer, 'mieitornei') !== false) ? 'mieitornei' : 'tornei';
        $page->add("content", new HeaderView("ui/titoloeindietro.mst", ['backUrl' => $backUrl, 'titolo' => "la mia squadra"]));
        if($this->squadraService->HaSquadra($user, $idTorneo)){
            $compagno = $this->squadraService->ottieniSquadra($user, $idTorneo);
            $page->add("content", new SquadreView("squadra/compagnosquadra", ['compagno'=>$compagno]));
            return $response;
        }
        $richiesteRicevute = $this->squadraService->ottieniRichiesteRicevute($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/richiestericevute", ['richiestericevute'=>$richiesteRicevute]));
        $richiesteInviate = $this->squadraService->ottieniRichiesteInviate($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/richiesteinviate", ['richiesteinviate'=>$richiesteInviate]));
        $giocatoriSingle = $this->squadraService->ottieniGiocatoriSingleNonMieiMittentiENonMieiDestinatari($user, $idTorneo);
        $page->add("content", new SquadreView("squadra/giocatorisingle", ['giocatorisingle'=>$giocatoriSingle]));
        return $response;
    }

    //AZIONI

    //POST

    public function inviaRichiesta(Request $request, Response $response, $args){
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $idTorneo = $data['idtorneo'];
        if($user->isGiocatore()){
            $idMittente = $user->getidgiocatore();
            $idDestinatario = $data['iddestinatario'];
            if($this->squadraService->inviaRichiesta($idTorneo, $idMittente, $idDestinatario)){
                UIMessage::setSuccess(REQUEST_SENT_SUCCESS);
            }else{
                UIMessage::setError(REQUEST_SENT_FAILED);
            }
        }else{
           UIMessage::setError(UNAUTHORIZED_OPERATION); 
        }
        return $response->withHeader("Location", "miasquadra?idtorneo=" . $idTorneo)->withStatus(302);
    }

    public function annullaRichiesta(Request $request, Response $response, $args){
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $idTorneo = $data['idtorneo'];
        if($user->isGiocatore()){
            $idRichiesta = $data['idrichiesta'];
            if($this->squadraService->annullaRichiesta($idRichiesta)){
                UIMessage::setSuccess(REQUEST_CANCEL_SUCCESS);
            }else{
                UIMessage::setError(REQUEST_CANCEL_FAILED);
            }
        }else{
           UIMessage::setError(UNAUTHORIZED_OPERATION); 
        }
        return $response->withHeader("Location", "miasquadra?idtorneo=" . $idTorneo)->withStatus(302);
    }

    public function accettaRichiesta(Request $request, Response $response, $args){
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $idTorneo = $data['idtorneo'];
        if($user->isGiocatore()){
            $idRichiesta = $data['idrichiesta'];
            if($this->squadraService->accettaRichiesta($idRichiesta)){
                UIMessage::setSuccess(REQUEST_ACCEPT_SUCCESS);
            }else{
                UIMessage::setError(REQUEST_ACCEPT_FAILED);
            }
        }else{
           UIMessage::setError(UNAUTHORIZED_OPERATION); 
        }
        return $response->withHeader("Location", "miasquadra?idtorneo=" . $idTorneo)->withStatus(302);
    }

    public function rifiutaRichiesta(Request $request, Response $response, $args){
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $idTorneo = $data['idtorneo'];
        if($user->isGiocatore()){
            $idRichiesta = $data['idrichiesta'];
            if($this->squadraService->rifiutaRichiesta($idRichiesta)){
                UIMessage::setSuccess(REQUEST_REJECT_SUCCESS);
            }else{
                UIMessage::setError(REQUEST_REJECT_FAILED);
            }
        }else{
           UIMessage::setError(UNAUTHORIZED_OPERATION); 
        }
        return $response->withHeader("Location", "miasquadra?idtorneo=" . $idTorneo)->withStatus(302);
    }
}

