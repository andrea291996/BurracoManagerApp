<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class ClassificaService{

    protected $classificaRepository;
    protected $squadraRepository;
    protected $squadraService;
    protected $torneoRepository;

    public function __construct()
    {
        $this->classificaRepository = new ClassificaRepository;
        $this->squadraRepository = new SquadraRepository;
        $this->squadraService = new SquadraService;
        $this->torneoRepository = new TorneoRepository;
    }

    public function ottieniClassifica($idTorneo, $user): array {
    $classificaGrezza = $this->classificaRepository->dammiClassifica($idTorneo);
    $data = [];
    $idMiaSquadra = null;
    if ($user->dimmiTipolgiaUtente() == "giocatore") {
        $miaSquadraObj = $this->squadraRepository->dammiSquadra($user->getidgiocatore(), $idTorneo);
        if ($miaSquadraObj) {
            $idMiaSquadra = $miaSquadraObj->getidsquadra();
        }
    }
    foreach ($classificaGrezza as $index => $c) {
        $idSquadraCorrente = $c->getidsquadra();
        $squadraObj = $this->squadraRepository->dammiSquadraPerSquadraId($idSquadraCorrente);
        $squadraDettaglio = $this->squadraService->ottieniSquadraCompleta($squadraObj);
        $data[] = [
            'posizione' => $index + 1,
            'is_mia_squadra' => ($idMiaSquadra !== null && $idSquadraCorrente == $idMiaSquadra),
            'nome1' => $squadraDettaglio['giocatoremittente']['nome'],
            'cognome1' => $squadraDettaglio['giocatoremittente']['cognome'],
            'nome2' => $squadraDettaglio['giocatoredestinatario']['nome'],
            'cognome2' => $squadraDettaglio['giocatoredestinatario']['cognome'],
            'totale' => $c->gettotale() ?? 0 
        ];
    }
    return ['nometorneo'=>$this->torneoRepository->dammiTorneo($idTorneo)->getnometorneo(), 'data'=>$data];
}

}