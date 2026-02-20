<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IscrizioneService{

    protected $squadraRepository;
    protected $torneoRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
    }
    /*
    public function ottieniIscrittiSingle($torneoId) {
        $iscritti = $this->torneoRepository->dammiUtentiIscritti($torneoId);
        $squadre = $this->squadraRepository->dammiSquadrePerTorneoId($torneoId);
        $idIscritti = [];
        $idIscrittiNonSingle = [];
        foreach($iscritti as $elemento){
            $idIscritti[] = $elemento->getidgiocatore();
        }
        if($squadre){
            foreach($squadre as $elemento){
            $idIscrittiNonSingle[] = $elemento['giocatoremittente']->getidgiocatore();
            $idIscrittiNonSingle[] = $elemento['giocatoredestinatario']->getidgiocatore();
            }
        }
        $iscrittiSingle = [];
        foreach($idIscritti as $id){
            if(!in_array($id, $idIscrittiNonSingle)){
                $single = new Accountgiocatori();
                $single->select(['idgiocatore'=>$id]);
                $single->setpassword(null);
                $iscrittiSingle['account'][] = [
                    "nome" => $single->getnome(),
                    "cognome" => $single->getcognome()
                ];
            }
        }
        
        return $iscrittiSingle;
        
    }
    */
    

    
}