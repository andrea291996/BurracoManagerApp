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
    protected $partiteRepository;
    protected $squadraRepository;
    protected $utentiRepository;
    protected $calendarioRepository;
    protected $distanzeRepository;
    protected $partiteService;

    public function __construct()
    {
        $this->torneoRepository = new TorneoRepository();
        $this->partiteRepository = new PartiteRepository();
        $this->squadraRepository = new SquadraRepository();
        $this->utentiRepository = new UtentiRepository();
        $this->calendarioRepository = new CalendarioRepository();
        $this->distanzeRepository = new DistanzeRepository();
        $this->partiteService = new PartiteService();
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
                'le_mie_partite' => $tipologiaUtente == "giocatore" && $isIscritto && $stato == STATUS_TOURNAMENT_ONGOING,
                'tutte_partite' => $tipologiaUtente == "amministratore"  && $stato == STATUS_TOURNAMENT_ONGOING,
                'calendario' => $stato == STATUS_TOURNAMENT_ONGOING,
                'classifica' => $stato == STATUS_TOURNAMENT_CLOSED || $stato == STATUS_TOURNAMENT_ONGOING,
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

    public function ottieniInfo($idTorneo, $user) {
        $tipologiaUtente = $user->dimmiTipolgiaUtente();
        $idTorneo = (int)$idTorneo;
        $torneo = $this->torneoRepository->dammiTorneo($idTorneo);
        $circoliIscritti = $this->utentiRepository->dammiCircoliIscrittiTorneo($idTorneo);
        $squadreIscritte = $this->squadraRepository->dammiSquadrePerTorneo($idTorneo);
        $partite = $this->partiteRepository->dammiPartitePerTorneo($idTorneo);
        
        $distanze = $this->distanzeRepository->dammiDistanzeSquadreCircoli($squadreIscritte, $circoliIscritti);
        
        $kmOttimizzati = 0;
        $kmDistribuzioneEqua = 0;
        $numCircoli = count($circoliIscritti);

        $viaggiSimulatiStandard = []; 

        foreach ($partite as $p) {
            $kmOttimizzati += ((float)$p->getdistanzapercorsainquinandodagiocatorimetri() / 1000);

            if ($numCircoli > 0) {
                $giornata = $p->getgiornata();
                $indiceCircoloRotazione = ($giornata - 1) % $numCircoli;
                $idCircoloStandard = $circoliIscritti[$indiceCircoloRotazione]->getidcircolo();
                $viaggiSimulatiStandard[$giornata][$p->getidsquadra1()] = $idCircoloStandard;
                $viaggiSimulatiStandard[$giornata][$p->getidsquadra2()] = $idCircoloStandard;
            }
        }

        foreach ($viaggiSimulatiStandard as $g => $squadreInViaggio) {
            foreach ($squadreInViaggio as $idSquadra => $idCircolo) {
                $kmDistribuzioneEqua += ($distanze[$idSquadra][$idCircolo] ?? 0);
            }
        }

        $risparmioPercentuale = ($kmDistribuzioneEqua > 0) 
            ? round((($kmDistribuzioneEqua - $kmOttimizzati) / $kmDistribuzioneEqua) * 100) 
            : 0;
        $mappaDati = ['circoli' => [], 'giocatori' => []];
        
        foreach ($circoliIscritti as $c) {
            $profilo = new Profilicircoli();
            $profilo->select(['idaccountcircolo' => $c->getidcircolo()]);
            if ($profilo->getlatitudine()) {
                $mappaDati['circoli'][] = [
                    'nome' => $c->getnome(),
                    'lat'  => (float)$profilo->getlatitudine(),
                    'lng'  => (float)$profilo->getlongitudine(),
                    'is_mio' => ($tipologiaUtente == 'circolo' && $user->getidcircolo() == $c->getidcircolo())
                ];
            }
        }

        if ($tipologiaUtente == 'amministratore' || $tipologiaUtente == 'giocatore') {
            $idMiaSquadra = ($tipologiaUtente == 'giocatore') 
                ? $this->squadraRepository->dammiSquadra($user->getidgiocatore(), $idTorneo)?->getidsquadra() 
                : null;

            foreach ($squadreIscritte as $s) {
                if ($tipologiaUtente == 'giocatore' && $s->getidsquadra() != $idMiaSquadra) continue;
                
                foreach ([$s->getidcompagnomittente(), $s->getidcompagnodestinatario()] as $idG) {
                    $pG = new Profiligiocatori();
                    $pG->select(['idaccountgiocatore' => $idG]);
                    if ($pG->getlatitudine()) {
                        $mappaDati['giocatori'][] = [
                            'lat' => (float)$pG->getlatitudine(),
                            'lng' => (float)$pG->getlongitudine(),
                            'soglia' => (int)($pG->getdistanzanoninquinanteinmetri() ?? 1000),
                            'is_me' => ($tipologiaUtente == 'giocatore' && $idG == $user->getidgiocatore())
                        ];
                    }
                }
            }
        }
        return [
            'nometorneo'        => $torneo->getnometorneo(),
            'km_totali'         => round($kmOttimizzati, 1),
            'km_standard'       => round($kmDistribuzioneEqua, 1),
            'risparmio_percent' => $risparmioPercentuale,
            'co2_emessa'        => round($kmOttimizzati * 0.12, 1), // Media 120g/km
            'co2_risparmiata'   => round(($kmDistribuzioneEqua - $kmOttimizzati) * 0.12, 1),
            'num_circoli'       => $numCircoli,
            'mappaDatiJson'     => json_encode($mappaDati)
        ];
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

    public function chiudiIscrizioni($idTorneo){ //SISTEMA IL RETURN
        $risultato = $this->torneoRepository->chiudiIscrizioni($idTorneo);
        if($risultato){
            $squadre = $this->squadraRepository->dammiSquadrePerTorneo($idTorneo);
            $circoli = $this->utentiRepository->dammiCircoliIscrittiTorneo($idTorneo);
            $distanze = $this->distanzeRepository->dammiDistanzeSquadreCircoli($squadre, $circoli);
            $algoritmo = new AlgoritmoOttimizzato($squadre, $circoli, $distanze, $idTorneo);
            $partite = $algoritmo->esegui();
            $this->partiteService->inserisciPartite($partite, $idTorneo);
            return $risultato;
        }
    }
}