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

    /*
    public function ottieniTuttiGiocatori(): array {
        $giocatori = $this->utentiRepository->dammiTuttiGiocatori();
        $data = [];
            if($giocatori){
                foreach($giocatori as $giocatore){
                    $data[] = [
                        "nome" => $giocatore->getnome(),
                        "cognome" => $giocatore->getcognome()
                    ];
                }
            }
        return $data;
    }
    */
    

    
}