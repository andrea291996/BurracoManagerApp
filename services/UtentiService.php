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

    public function ottieniGiocatoriIscrittiTorneo($idTorneo): array{
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

    public function ottieniCircoliIscrittiTorneo($idTorneo): array{
        $iscritti = $this->utentiRepository->dammiCircoliIscrittiTorneo($idTorneo);
        $data = [];
        foreach($iscritti as $iscritto){
            $data[] = [
                'nome' => $iscritto->getnome(),
            ];
        }
        return $data;
    }

    public function ottieniGiocatoriSenzaSquadra($idTorneo): array{
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

    public function ottieniTuttiUtentiGiocatori(): array{
        $utentiGiocatori = $this->utentiRepository->dammiTuttiGiocatori();
        $data = [];
        foreach($utentiGiocatori as $giocatore){
            $data[] = [
                'nome' => $giocatore->getnome(),
                'cognome'=>$giocatore->getcognome()
            ];
        }
        return $data;
    }

    public function ottieniTuttiUtentiCircoli(): array{
        $utentiCircoli = $this->utentiRepository->dammiTuttiCircoli();
        $data = [];
        foreach($utentiCircoli as $circolo){
            $data[] = [
                'nome' => $circolo->getnome(),
            ];
        }
        return $data;
    }
}