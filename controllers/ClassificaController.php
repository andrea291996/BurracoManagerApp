<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClassificaController extends Controller{
    protected $classificaService;

    public function __construct()
    {
        $this->classificaService = new ClassificaService;
    }
    
    function mostraClassifica(Request $request, Response $response, $args) {    
        $page = PageConfigurator::instance()->getPage(); 
        $page->setTitle("Classfica");
        $data = $request->getQueryParams();
        $torneoId = $data['idtorneo'];
        $user = $request->getAttribute('user');
        $classifica = $this->classificaService->ottieniClassifica($torneoId, $user);
        $page->add("content", new HeaderView("ui/titoloeindietro", ['backUrl' => 'tornei', 'titolo' => "Classifica"]));
        $page->add("content", new ClassificaView("classifica/classifica", ['data' => $classifica['data'], 'nometorneo'=>$classifica['nometorneo'],'is_admin' => ($user->dimmiTipolgiaUtente() == "amministratore")]));
        return $response;
        }
        
}

