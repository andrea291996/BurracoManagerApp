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
        
    }

    public function dammiIdSquadraAvversaria($idSquadra, $idPartita){
        $partita = new Partite();
        $partita->select(['idpartita' => $idPartita]);
        $idSquadra1 = $partita->getsquadra1();
        $idSquadra2 = $partita->getsquadra2();
        if($idSquadra == $idSquadra1){
            return $idSquadra2;
        }elseif($idSquadra == $idSquadra2){
            return $idSquadra1;
        }
    }

    //AZIONI

    public function inserisciPartita($partitaArr, $idTorneo): bool{
        $partita = new Partite();
        $partita->setsquadra1($partitaArr['squadra1']);
        $partita->setsquadra2($partitaArr['squadra2']);
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
            $idSquadraAvversaria = $this->dammiIdSquadraAvversaria($idSquadra, $idPartita);
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

    

}