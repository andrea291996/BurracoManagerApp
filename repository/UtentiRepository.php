<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

Dammi tutti i giocatori
Dammi tutti i circoli
Dammi giocatori iscritti al torneo per torneoId (parametro per dare tutti tranne $userId)
Dammi circoli iscritti al torneo per torneoId

*/

class UtentiRepository{
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
    }

    public function dammiTuttiGiocatori(): array{
        $utentiGrezzi = $this->database->select("accountgiocatori", [], []);
        $utenti = [];
        if(!empty($utentiGrezzi)){
            foreach($utentiGrezzi as $elemento){
            $utente = new Accountgiocatori();
            $utente->select(['idgiocatore' => $elemento['idgiocatore']]);
            $utente->setpassword(null);
            $utenti[] = $utente;
            }
            return $utenti;
        }
        return [];
    }

    public function dammiTuttiCircoli(): array{
        $circoliGezzi = $this->database->select("accountcircoli", [], []);
        $circoli = [];
        if(!empty($circoliGezzi)){
            foreach($circoliGezzi as $elemento){
            $circolo = new Accountcircoli();
            $circolo->select(['idcircolo' => $elemento['idcircolo']]);
            $circolo->setpassword(null);
            $circoli[] = $circolo;
            }
            return $circoli;
        }
        return [];
    }

    public function dammiGiocatoriIscrittiTorneo($torneoId, $userId = false): array{
        if($userId){
            $sql = "SELECT idgiocatore FROM giocatoretorneo WHERE idtorneo = ? AND idgiocatore != ?";
            $sth = $this->database->prepare($sql);
            $sth->execute([$torneoId, $userId]);
        }else{
            $sql = "SELECT idgiocatore FROM giocatoretorneo WHERE idtorneo = ?";
            $sth = $this->database->prepare($sql);
            $sth->execute([$torneoId]);
        }
        $idGiocatoriIscrittiGrezzi = $sth->fetchAll();
        if(!empty($idGiocatoriIscrittiGrezzi)){
            $giocatoriIscrittiGrezzi = [];
            foreach($idGiocatoriIscrittiGrezzi as $elemento){
                $giocatoreIscritto = new Accountgiocatori();
                $giocatoreIscritto->select(['idgiocatore'=>$elemento['idgiocatore']]);
                $giocatoreIscritto->setpassword(null);
                $giocatoriIscrittiGrezzi[] = $giocatoreIscritto;
            }
            return $giocatoriIscrittiGrezzi;
        }
        return [];
    }

    public function dammiCircoliIscrittiTorneo($torneoId): array{
        $idCircoliIscrittiGrezzi = $this->database->select("circolotorneo", [],['idtorneo' => $torneoId]);
        $circoliIscrittiGrezzi = [];
        if(!empty($circoliIscrittiGrezzi)){
            foreach($idCircoliIscrittiGrezzi as $elemento){
                $circoloIscritto = new Accountcircoli();
                $circoloIscritto->select(['idcircolo'=>$elemento['idcircolo']]);
                $circoloIscritto->setpassword(null);
                $circoliIscrittiGrezzi[] = $circoloIscritto;
            }
            return $circoliIscrittiGrezzi;
        }
        return [];
    }

    //VECCHIA
    /*
    public function dammiTuttiUtenti(){
        $utentiGezzi = $this->database->select("accountgiocatori", [], []);
        $utenti = [];
        foreach($utentiGezzi as $elemento){
            $utente = new Accountgiocatori();
            $utente->select(['idgiocatore' => $elemento['idgiocatore']]);
            $utente->setpassword(null);
            $utenti[] = $utente;
        }
        return $utenti;
    }
    */
}