<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

ottieni dati mia squadra (squadra, compagno, mittenti, destinatari, single) per userId e per TorneoId
ottieni tutte squadre per torneoId
ottieni tutte squadre

*/

class SquadraService{

    protected $squadraRepository;
    protected $torneoRepository;
    protected $utentiRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
        $this->utentiRepository = new UtentiRepository();
    }

    /*
    public function ottieniDatiMiaSquadra($userId, $torneoId) {
        $squadra = $this->squadraRepository->dammiSquadra($userId, $torneoId);
        $compagno = $this->squadraRepository->dammiCompagnoSquadra($userId, $torneoId);
        $mittenti = $this->squadraRepository->dammiMieiMittenti($userId, $torneoId);
        $destinatari = $this->squadraRepository->dammiMieiDestinatari($userId, $torneoId);
        $giocatoriSenzaDiMe = $this->utentiRepository->dammiGiocatoriIscrittiTorneo($torneoId, $userId);
        $filtrati = [];
            if(!empty($giocatoriSenzaDiMe)){
                foreach($giocatoriSenzaDiMe as $giocatore){
                    if(!in_array($giocatore, $mittenti) || !in_array($giocatore, $destinatari)){
                        $filtrati[] = $giocatore;
                    }
                }
            }
        return [
            'squadra' => $squadra,
            'compagno' => $compagno,
            'mittenti' => $mittenti,
            'destinatari' => $destinatari,
            'single' => $filtrati
        ];
    }

    public function ottieniTutteSquadrePerTorneo($torneoId){
        return $this->squadraRepository->dammiSquadrePerTorneo($torneoId);
    }

    public function ottieniTutteSquadre(){
        return $this->squadraRepository->dammiTutteSquadre();
    }

    */
}