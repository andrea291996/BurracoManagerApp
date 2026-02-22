<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
ottieni tutti i giocatori
ottieni tutti i circoli
*/

class PartiteService{

    protected $utentiRepository;
    protected $squadraRepository;
    protected $partiteRepository;

    public function __construct()
    {
        $this->utentiRepository = new UtentiRepository;
        $this->squadraRepository = new SquadraRepository;
         $this->partiteRepository = new PartiteRepository;
    }

    public function preparaPartite($partite, $idTorneo){
        foreach($partite as $partita){
            $this->partiteRepository->inserisciPartita($partita, $idTorneo);
        }
    }
}