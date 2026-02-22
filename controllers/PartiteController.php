<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartiteController extends Controller{
    protected $partiteService;

    public function __construct()
    {
        $this->partiteService = new PartiteService();
    }
    
    function mostraMiePartite(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Le mie partite");
        $user = $request->getAttribute('user');
        $idTorneo = $request->getQueryParams('idtorneo');
        $idTorneo = $idTorneo['idtorneo'];
        $partite = $this->partiteService->ottieniPartite($user, $idTorneo);
        $page->add("content", new PartiteView("partite/partite", $partite));
        return $response;
        }
        
}

