<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

ottieni miei tornei per userId
ottieni tutti tornei
ottieni info torneo per torneoId
ottieni giocatori iscritti al torneo per torneoId
ottieni circoli iscritti al torneo per torneo Id

*/

class TorneoService{

    protected $torneoRepository;

    public function __construct()
    {
        $this->torneoRepository = new TorneoRepository();
    }

    public function ottieniTornei($user, $miei = false): array{
        /*
        DATA
            nome torneo
            stato
            pulsante_info
            classifica

            la mia squadra
            calendario
            iscriviti
            disiscriviti
            le mie partite
            iscriviti
            disiscriviti

            chiudi iscrizioni
            tutte le squadre
            utenti iscritti
            utenti iscritti ma senza compagno
            squadre iscritte
            circoli iscritti
        */
        $tipologiaUtente = $user->dimmiTipolgiaUtente();
        if($miei){
            if($tipologiaUtente == "giocatore"){
                $torneiGrezzi = $this->torneoRepository->dammiTorneiDiGiocatore($user->getidgiocatore());
            }elseif($tipologiaUtente == "circolo"){
                $torneiGrezzi = $this->torneoRepository->dammiTorneiDiCircolo($user->getidcircolo());
            }
        }else{
            $torneiGrezzi = $this->torneoRepository->dammiTuttiTornei();
        }
        $data = [];
        foreach($torneiGrezzi as $torneo){
            $stato = $torneo->getstatotorneo();
            $idTorneo = $torneo->getidtorneo();
            $isIscritto = false;
            if($tipologiaUtente == "giocatore"){
                $isIscritto = $this->torneoRepository->IsGiocatoreIscritto($idTorneo, $user->getidgiocatore());
            }elseif($tipologiaUtente == "circolo"){
                $isIscritto = $this->torneoRepository->IsCircoloIscritto($idTorneo, $user->getidcircolo());
            }
            $data[] = [
                'nome_torneo' => $torneo->getnometorneo(),
                'id_torneo' => $idTorneo,
                'stato_torneo' => $torneo->getstatotorneo(),
                'pulsante_info' => true,
                'la_mia_squadra' => $tipologiaUtente == "giocatore" && $isIscritto,
                'calendario' => $stato == STATUS_TOURNAMENT_ONGOING,
                'classifica' => $stato == STATUS_TOURNAMENT_CLOSED,
                'isGiocatoreOCircoloIscritto' =>
                 ($stato == STATUS_TOURNAMENT_OPEN && !$isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo")) || 
                 ($stato == STATUS_TOURNAMENT_OPEN && $isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo")) || 
                 ($tipologiaUtente == "giocatore" && $isIscritto),
                'iscriviti' => $stato == STATUS_TOURNAMENT_OPEN && !$isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo"),
                'disiscriviti' => $stato == STATUS_TOURNAMENT_OPEN && $isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo"),
                'chiudi_iscrizioni' => $stato == STATUS_TOURNAMENT_OPEN && $tipologiaUtente == "amministratore",
                'tutte_squadre' => $tipologiaUtente == "amministratore",
                'utenti_iscritti' => $tipologiaUtente == "amministratore",
                'utenti_iscritti_ma_single' => $tipologiaUtente == "amministratore" && $stato == STATUS_TOURNAMENT_OPEN,
                'circoli_iscritti' => $tipologiaUtente == "amministratore",
                'isAmministratore' => $tipologiaUtente == "amministratore"
            ];
        }
        
        return ['data' => $data,'template' => "tornei/tuttitornei"];
    }

    //AZIONI

    public function iscrivi($torneoId, $user): bool{
        if($user->isGiocatore()){
            return (bool)$this->torneoRepository->iscriviGiocatore($torneoId, $user->getidgiocatore());
        }elseif($user->isCircolo()){
            return (bool)$this->torneoRepository->iscriviCircolo($torneoId, $user->getidcircolo());
        }
        return false;
    }

    public function disiscrivi($torneoId, $user): bool{
        if($user->isGiocatore()){
            return (bool)$this->torneoRepository->disiscriviGiocatore($torneoId, $user->getidgiocatore());
        }elseif($user->isCircolo()){
            return (bool)$this->torneoRepository->disiscriviCircolo($torneoId, $user->getidcircolo());
        }
        return false;
    }

    public function creaNuovoTorneo($nomeTorneo): bool{
        return $this->torneoRepository->inserisciNuovoTorneo($nomeTorneo);
    }
}