<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
tabella partite
tabella validazionepunteggi
tabella punteggiopartita per squadra
*/

class PartiteRepository{

    protected $squadraRepository;
    protected $torneoRepository;
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
    }

    public function dammiPartitePerTorneo($idTorneo){
        $sql = "SELECT * FROM partite WHERE idtorneo = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idTorneo]);
        $partiteGrezze = $sth->fetchAll();
        $partite = [];
        foreach($partiteGrezze as $partitaGrezza){
            $partita = $this->dammiPartitaPerId($partitaGrezza['idpartita']);
            $partite[] = $partita;
        }
        return $partite;
    }

    public function dammiPartitaPerId($idPartita): Partite{
        $partita = new Partite();
        $partita->select(['idpartita' => $idPartita]);
        return $partita;
    }

    public function dammiPunteggioSquadraPerPartita($idSquadra, $idPartita){
        $sql = "SELECT punteggio FROM punteggipartita WHERE idsquadra = ? AND idpartita = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idSquadra, $idPartita]);
        $risultato = $sth->fetch();
        if(empty($risultato)){
            return false;
        }else{
            return $risultato['punteggio'];
        }
    }

    

    //AZIONI

    public function inserisciPartita($partitaArr, $idTorneo): bool{
        $partita = new Partite();
        $partita->setidsquadra1($partitaArr['squadra1']);
        $partita->setidsquadra2($partitaArr['squadra2']);
        $partita->setgiornata($partitaArr['giornata']);
        $partita->setturno($partitaArr['turno']);
        $partita->setdistanzapercorsainquinandodagiocatorimetri($partitaArr['km_totali']*1000);
        $partita->setidcircolo($partitaArr['id_circolo']);
        $partita->setidtorneo($idTorneo);
        return $partita->insert() > 0;
    }

    public function inserisciPunteggioPartita($idPartita, $idSquadra, $punteggio): bool{
        $punteggioPartita = new Punteggiopartita();
        $punteggioPartita->setidpartita($idPartita);
        $punteggioPartita->setidsquadra($idSquadra);
        $punteggioPartita->setpunteggio($punteggio);
        return $punteggioPartita->insert() > 0;
    }

    public function inserisciMioPunteggio($idGiocatore, $idPartita, $punteggio, $idTorneo){
        if($this->HaInseritoPunteggio($idGiocatore, $idPartita)){
            UIMessage::setError(SCORE_ALREADY_EXIST);
        }else{
            $squadra = $this->squadraRepository->dammiSquadra($idGiocatore, $idTorneo);
            $idSquadra = $squadra->getidsquadra();
            $idSquadraAvversaria = $this->squadraRepository->dammiIdSquadraAvversaria($idSquadra, $idPartita);
            $mioPunteggio = new Validazionepunteggi();
        }
    }

    public function HaInseritoPunteggio($idGiocatore, $idPartita): bool{
        $sql = "SELECT * FROM validazionepunteggi WHERE idgiocatore = ? AND idpartita = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idGiocatore, $idPartita]);
        $risultato = $sth->fetch();
        if(empty($risultato)){
            return false;
        }else{
            return true;
        }
    }   

    public function esistePunteggioPartita($idPartita){
        $sql = "SELECT * FROM punteggipartita WHERE idpartita = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idPartita]);
        $risultato = $sth->fetchAll();
        if(count($risultato) === 2){
            return true;
        }else{
            return false;
        }
    }

    

}