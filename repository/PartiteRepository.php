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
    protected $classificaRepository;
    protected $torneoRepository;
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
        $this->classificaRepository = new ClassificaRepository();
    }

    public function dammiPartitePerTorneo($idTorneo){
        $sql = "SELECT * FROM partite WHERE idtorneo = ? ORDER BY giornata, turno";
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
        $idTorneo = $this->dammiPartitaPerId($idPartita)->getidtorneo();
        $this->classificaRepository->aggiornaTotale($idTorneo, $idSquadra, $punteggio);
        $punteggioPartita = new Punteggipartita();
        $punteggioPartita->setidpartita($idPartita);
        $punteggioPartita->setidsquadra($idSquadra);
        $punteggioPartita->setpunteggio($punteggio);
        return $punteggioPartita->insert();
    }

    public function inserisciMioPunteggio($data, $user): bool{
        $idGiocatore = $user->getidgiocatore();
        $idPartita = $data['idpartita'];
        if($this->HaInseritoPunteggio($idGiocatore, $idPartita)){
            UIMessage::setError(SCORE_ALREADY_EXIST);
        }else{
            $idMiaSquadra = $data['idmiasquadra'];
            $idSquadraAvversaria = $data['idsquadraavversaria'];
            $puntiMiaSquadra = $data['puntimiasquadra'];
            $puntiSquadraAvversaria = $data['puntisquadraavversaria'];
            if($puntiMiaSquadra < 0 || $puntiSquadraAvversaria < 0){
                UIMessage::setError(SCORE_INSERT_NEGATIVE);
                return false;
            }
            $idTorneo = $data['idtorneo'];
            $partita = $this->dammiPartitaPerId($idPartita);
            $idSquadra1 = $partita->getidsquadra1();
            $idSquadra2 = $partita->getidsquadra2();
            if($idMiaSquadra == $idSquadra1){
                $idSquadra1 = $idMiaSquadra;
                $idSquadra2 = $idSquadraAvversaria;
                $punteggiosquadra1 = $puntiMiaSquadra;
                $punteggiosquadra2 = $puntiSquadraAvversaria;
            }else{
                $idSquadra1 = $idSquadraAvversaria;
                $idSquadra2 = $idMiaSquadra;
                $punteggiosquadra1 = $puntiSquadraAvversaria;
                $punteggiosquadra2 = $puntiMiaSquadra;
            }
            $validazionePunteggi = new Validazionepunteggi();
            $validazionePunteggi->setidgiocatore($idGiocatore);
            $validazionePunteggi->setidpartita($idPartita);
            $validazionePunteggi->setidsquadra1($idSquadra1);
            $validazionePunteggi->setidsquadra2($idSquadra2);
            $validazionePunteggi->setpunteggiosquadra1($punteggiosquadra1);
            $validazionePunteggi->setpunteggiosquadra2($punteggiosquadra2);
            $risultato = $validazionePunteggi->insert();
        }
        if($risultato){
            return true;
        }else{
            return false;
        }
    }

    public function hannoTuttiIGiocatoriInseritoIlPunteggio($idPartita): bool{
        $sql = "SELECT count(*) FROM validazionepunteggi WHERE idpartita = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idPartita]);
        $risultato = $sth->fetchColumn();
        if($risultato == 4){
            return true;
        }else{
            return false;
        }
    }

    public function controllaSePunteggiCoincidono($idPartita): bool{
        $sql = "SELECT * FROM validazionepunteggi WHERE idpartita = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idPartita]);
        $data = $sth->fetchAll();
        $idSquadra1 = $data[0]['idsquadra1'];
        $idSquadra2 = $data[0]['idsquadra2'];
        $punteggiSquadra1 = [];
        $punteggiSquadra2 = [];
        foreach($data as $r){
            $riga = new Validazionepunteggi();
            $riga->select(['idvalidazionepunteggi'=>$r['idvalidazionepunteggi']]);
            $punteggiosquadra1 = $riga->getpunteggiosquadra1();
            $punteggiosquadra2 = $riga->getpunteggiosquadra2();
            $punteggiSquadra1[] = $punteggiosquadra1;
            $punteggiSquadra2[] = $punteggiosquadra2;
        }
        if (count(array_unique($punteggiSquadra1)) === 1 && count(array_unique($punteggiSquadra2)) === 1){
            $this->inserisciPunteggioPartita($idPartita, $idSquadra1, $data[0]['punteggiosquadra1']);
            $this->inserisciPunteggioPartita($idPartita, $idSquadra2, $data[0]['punteggiosquadra2']);
            foreach($data as $r){
            $riga = new Validazionepunteggi();
            $riga->select(['idvalidazionepunteggi'=>$r['idvalidazionepunteggi']]);
            $riga->delete();
            }
            return true;
        }else{
            UIMessage::setError(SCORE_MISMATCH);
            foreach($data as $r){
            $riga = new Validazionepunteggi();
            $riga->select(['idvalidazionepunteggi'=>$r['idvalidazionepunteggi']]);
            $riga->delete();
            }
            return false;
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