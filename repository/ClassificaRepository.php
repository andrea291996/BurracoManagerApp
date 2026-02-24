<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClassificaRepository{

    protected $squadraRepository;
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
        $this->squadraRepository = new SquadraRepository();
    }

    public function dammiIdTotale($idSquadra, $idTorneo){
        $sql = "SELECT idtotale FROM totali WHERE idsquadra = ? AND idtorneo = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idSquadra, $idTorneo]);
        return $sth->fetchColumn();
    }

    public function dammiClassifica($torneoId){
        $sql = "SELECT * FROM totali WHERE idtorneo = ? ORDER BY totale DESC";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId]);
        $totaliInOrdineDalPiùAlto = $sth->fetchAll();
        $classifica = [];
        foreach($totaliInOrdineDalPiùAlto as $t){
            $totale = new Totali();
            $totale->select(['idtotale'=>$t['idtotale']]);
            $classifica[] = $totale;
        }
        return $classifica;
    }

    //AZIONI
    public function creaClassifica($idTorneo){
        $squadre = $this->squadraRepository->dammiSquadrePerTorneo($idTorneo);
        foreach($squadre as $squadra){
            $totale = new Totali();
            $idSquadra = $squadra->getidsquadra();
            $totale->setidsquadra($idSquadra);
            $totale->setidtorneo($idTorneo);
            $totale->insert();
        }
    }

    public function aggiornatotale($idTorneo, $idSquadra, $punteggio){
        $totale = new Totali();
        $totale->select(['idtotale'=>$this->dammiIdTotale($idSquadra, $idTorneo)]);
        $punteggioVecchio = $totale->gettotale();
        if($punteggioVecchio == null){
            $punteggioVecchio = 0;
        }
        $punteggioNuovo = $punteggioVecchio + $punteggio;
        $totale->settotale($punteggioNuovo);
        $totale->update();
    }
}