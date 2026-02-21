<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
ottieni tutti i giocatori
ottieni tutti i circoli
*/

class UtentiService{

    protected $utentiRepository;

    public function __construct()
    {
        $this->utentiRepository = new UtentiRepository;
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
}