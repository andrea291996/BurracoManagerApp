<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CalendarioService{

    protected $utentiRepository;
    protected $squadraRepository;
    protected $partiteRepository;
    protected $classificaRepository;
    protected $squadraService;
    protected $torneoRepository;

    public function __construct()
    {
        $this->utentiRepository = new UtentiRepository;
        $this->squadraRepository = new SquadraRepository;
        $this->squadraService = new SquadraService;
        $this->partiteRepository = new PartiteRepository;
        $this->classificaRepository = new ClassificaRepository;
        $this->torneoRepository = new TorneoRepository;
    }

    public function ottieniCalendario($idTorneo, $user) {
    $partite = $this->partiteRepository->dammiPartitePerTorneo($idTorneo);
    $partiteArray = [];
    $idMiaSquadra = null;
    if ($user->dimmiTipolgiaUtente() == "giocatore") {
        $miaSquadraObj = $this->squadraRepository->dammiSquadra($user->getidgiocatore(), $idTorneo);
        if ($miaSquadraObj) {
            $idMiaSquadra = $miaSquadraObj->getidsquadra();
        }
    }
    foreach ($partite as $partita) {
        $idSquadra1 = $partita->getidsquadra1();
        $idSquadra2 = $partita->getidsquadra2();
        $isMiaPartita = ($idMiaSquadra !== null && ($idMiaSquadra == $idSquadra1 || $idMiaSquadra == $idSquadra2));
        $squadra1Obj = $this->squadraRepository->dammiSquadraPerSquadraId($idSquadra1);
        $squadra2Obj = $this->squadraRepository->dammiSquadraPerSquadraId($idSquadra2);
        $squadra1Dettaglio = $this->squadraService->ottieniSquadraCompleta($squadra1Obj);
        $squadra2Dettaglio = $this->squadraService->ottieniSquadraCompleta($squadra2Obj);
        $circolo = $this->utentiRepository->dammiCircoloPerId($partita->getidcircolo());
        $partiteArray[] = [
            'giornata'=> $partita->getgiornata(),
            'turno'  => $partita->getturno(),
            'nome_circolo'  => $circolo->getnome(),
            'is_mia_partita' => $isMiaPartita,
            's1_n1' => $squadra1Dettaglio['giocatoremittente']['nome'],
            's1_c1' => $squadra1Dettaglio['giocatoremittente']['cognome'],
            's1_n2' => $squadra1Dettaglio['giocatoredestinatario']['nome'],
            's1_c2' => $squadra1Dettaglio['giocatoredestinatario']['cognome'],
            's2_n1' => $squadra2Dettaglio['giocatoremittente']['nome'],
            's2_c1' => $squadra2Dettaglio['giocatoremittente']['cognome'],
            's2_n2' => $squadra2Dettaglio['giocatoredestinatario']['nome'],
            's2_c2' => $squadra2Dettaglio['giocatoredestinatario']['cognome'],
            ];
    }
        return ['nometorneo' => $this->torneoRepository->dammiTorneo($idTorneo)->getnometorneo(),'partite'=> $partiteArray, 'statotorneo' => $this->torneoRepository->dammiStatoTorneo($idTorneo)];
    }

}