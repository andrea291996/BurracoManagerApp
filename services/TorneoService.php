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
                'calendario' => $stato == "in corso",
                'classifica' => $stato == "concluso",
                'isGiocatoreOCircoloIscritto' =>
                 ($stato == "aperto" && !$isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo")) || 
                 ($stato == "aperto" && $isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo")) || 
                 ($tipologiaUtente == "giocatore" && $isIscritto),
                'iscriviti' => $stato == "aperto" && !$isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo"),
                'disiscriviti' => $stato == "aperto" && $isIscritto && ($tipologiaUtente == "giocatore" || $tipologiaUtente == "circolo"),
                'chiudi_iscrizioni' => $stato == "aperto" && $tipologiaUtente == "amministratore",
                'tutte_squadre' => $tipologiaUtente == "amministratore",
                'utenti_iscritti' => $tipologiaUtente == "amministratore",
                'utenti_iscritti_ma_single' => $tipologiaUtente == "amministratore" && $stato == "aperto",
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


    /*
    public function mostraMieiTornei($user){
        $tornei = $this->mostraTorneiPerTipologiaUtente($user, true);
        return $tornei;
    }

    public function mostraTorneiPerTipologiaUtente($user, $soloIscritti = false){
        $torneiGrezzi = $this->torneoRepository->dammiTuttiTornei();
        $torneiDecorati = [];
        
        foreach($torneiGrezzi as $torneo){
            $stato = $torneo->getstatotorneo();
            $idTorneo = $torneo->getidtorneo();
            $isIscritto = false; 
                if($user->isGiocatore()){
                    $isIscritto = $this->torneoRepository->isIscritto($idTorneo, $user->getidgiocatore(), "giocatore");
                } elseif($user->isCircolo()){
                    $isIscritto = $this->torneoRepository->isIscritto($idTorneo, $user->getidcircolo(), "circolo");
                }
            if($soloIscritti == true && $isIscritto == false) {
            continue; // Questo comando interrompe il ciclo per questo torneo e passa al prossimo
            }
            $data = [
                'idtorneo'   => $idTorneo,
                'nometorneo' => $torneo->getnometorneo(),
                'stato'      => $stato,
                'bottone_info' => true
            ];
            if($user->isAnonimo()){
                $data['bottone_classifica'] = $stato == "concluso" || $stato == 'in corso';
            }
            if($user->isAmministratore()){
                    $data['bottone_classifica'] = $stato == "concluso" || $stato == 'in corso';
                    $data['bottone_calendario'] = $stato == "concluso" || $stato == "in corso";
                    $data['bottone_partite'] = $stato == "concluso" || $stato == "in corso";
                    $data['bottone_squadre'] = true;
                    $data['bottone_utenti_iscritti'] = true; 
                    $data['bottone_circoli_iscritti'] = true;
                    $data['bottone_utenti_single'] = $stato == "aperto";
                    $data['bottone_chiudi_iscrizioni'] = $stato == "aperto";
                    $data['bottone_termina_torneo'] = $stato == "in corso";
                    
                }
            if($user->isGiocatore()){
                    $data['iscritto_badge'] = $isIscritto;
                    $data['bottone_classifica'] = $stato == "concluso" || ($stato == 'in corso' && $isIscritto);
                    $data['bottone_calendario'] = $stato == "in corso" && $isIscritto;
                    $data['bottone_partite'] = ($stato == "concluso" || $stato == "in corso") && $isIscritto;
                    $data['bottone_mie_partite'] = ($stato == "concluso" || $stato == "in corso") && $isIscritto;
                    $data['bottone_mia_squadra'] = $isIscritto;
                    $data['bottone_iscriviti'] = (!$isIscritto && $stato == "aperto");
                    $data['bottone_disiscriviti'] = ($isIscritto && $stato == "aperto");
                
            }
            if($user->isCircolo()){            
                    $data['iscritto_badge'] = $isIscritto;
                    $data['bottone_classifica'] = $stato == "concluso" || ($stato == 'in corso' && $isIscritto);
                    $data['bottone_calendario'] = $stato == "in corso" && $isIscritto;
                    $data['bottone_iscriviti'] = (!$isIscritto && $stato == "aperto");
                    $data['bottone_disiscriviti'] = ($isIscritto && $stato == "aperto");
            }
                $torneiDecorati[] = $data;
        }
        return ['data' => $torneiDecorati, 'template' => "tornei/tuttitornei"];
    }

    public function infoTorneo($idTorneo){
        $torneo = $this->torneoRepository->dammiTorneoPerId($idTorneo);
        $data['nome'] = $torneo->getnometorneo();
        $data['stato'] = $torneo->getstatotorneo();
        $dataDecorati[] = $data;
        return ['data' => $dataDecorati, 'template' => "tornei/torneo"];
    }

    public function utentiIscritti($idTorneo){
        $iscritti = $this->torneoRepository->dammiUtentiIscritti($idTorneo);
        $data = [];
        foreach($iscritti as $iscritto){
            $data[] = [
                'nome'=>$iscritto->getnome(),
                'cognome'=>$iscritto->getcognome()
            ];
        }
        return [
            'data'=>[
            'iscritti' => $data, 
            'idtorneo' => $idTorneo],
            'template' => "utenti/utentiiscritti"];
    }

    public function circoliIscritti($idTorneo){
        $iscritti = $this->torneoRepository->dammiCircoliIscritti($idTorneo);
        $data = [];
        foreach($iscritti as $iscritto){
            $data[] = [
                'nome'=>$iscritto->getnome()
            ];
        }
        return [
            'data'=>[
                'iscritti' => $data, 
                'idtorneo' => $idTorneo
            ],
        'template' => "circoli/circoliiscritti"
    ];
    }
    */
}