<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CalendarioController extends Controller{
    protected $calendarioService;

    public function __construct()
    {
        $this->calendarioService = new CalendarioService();
    }
    
    public function mostraCalendario(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Calendario");
        $user = $request->getAttribute('user');
        $data = $request->getQueryParams();
        $idTorneo = $data['idtorneo'];
        $referer = $request->getHeaderLine('Referer');
        $backUrl = (strpos($referer, 'mieitornei') !== false) ? 'mieitornei' : 'tornei';
        $calendario = $this->calendarioService->ottieniCalendario($idTorneo, $user);
        $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => $backUrl, 'titolo' => "Calendario"]));
        $page->add("content", new CalendarioView("calendario/calendario", $calendario));
        return $response;
        }
        
}

