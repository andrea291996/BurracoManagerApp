<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
ottieni tutti i giocatori
ottieni tutti i circoli
*/

class UtentiService{

    protected $utentiRepository;
    protected $squadraRepository;

    public function __construct()
    {
        $this->utentiRepository = new UtentiRepository;
        $this->squadraRepository = new SquadraRepository;
    }

    public function ottieniGiocatoriIscrittiTorneo($idTorneo){
        $iscritti = $this->utentiRepository->dammiGiocatoriIscrittiTorneo($idTorneo);
        $data = [];
        foreach($iscritti as $iscritto){
            $data[] = [
                'nome' => $iscritto->getnome(),
                'cognome'=>$iscritto->getcognome()
            ];
        }
        return $data;
    }

    public function ottieniGiocatoriSenzaSquadra($idTorneo){
        $giocatori = $this->squadraRepository->dammiGiocatoriSenzaSquadra($idTorneo);
        $data = [];
        foreach($giocatori as $giocatore){
            $data[] = [
                'nome' => $giocatore->getnome(),
                'cognome'=>$giocatore->getcognome()
            ];
        }
        return $data;
    }
}