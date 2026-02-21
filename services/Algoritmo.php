<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

ottieni dati mia squadra (squadra, compagno, mittenti, destinatari, single) per userId e per TorneoId
    ottieni richieste ricevute
    ottieni richieste inviate
    ottieni compagni single
    ottieni compagno squadra
ottieni tutte squadre per torneoId
ottieni tutte squadre

*/

class Algoritmo{

    protected $squadraRepository;
    protected $torneoRepository;
    protected $utentiRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
        $this->utentiRepository = new UtentiRepository();
    }

    static function generaCalendario(){

    }


}
    