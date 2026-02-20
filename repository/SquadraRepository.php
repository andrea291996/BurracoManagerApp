<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

Dammi tutte squadre
Dammi squadre per torneoId
Dammi squadra per giocatoreId e torneoId
Dammi compagno di squadra per userId e torneoId
Dammi miei mittenti richiestasquadra per torneoId e userId
Dammi miei destinatari richiestasquadra per torneoId e userId
Ha una squadra? per torneoId useriId

*/

class SquadraRepository{
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
    }

    public function dammiTutteSquadre(): array{
        $squadreGrezze = $this->database->select(['squadre'],[],[]);
        $squadre = [];
        if(!empty($squadreGrezze)){
            foreach($squadreGrezze as $elemento){
            $squadra = new Squadre();
            $squadra->select(['idsquadra'=>$elemento['idsquadra']]);
            $squadre[] = $squadra;
            }
            return $squadre;
        }
        return [];
        
    }

    public function dammiSquadrePerTorneo($torneoId): array{
        $squadreGrezze = $this->database->select(['squadre'],[],['idtorneo' => $torneoId]);
        $squadre = [];
        if(!empty($squadreGrezze)){
            foreach($squadreGrezze as $elemento){
                $squadra = new Squadre();
                $squadra->select(['idsquadra'=>$elemento['idsquadra']]);
                $squadre[] = $squadra;
            }
            return $squadre;
        }
        return [];
    }

    public function dammiSquadra($userId, $torneoId): Squadre | null{
        $squadreGrezze = $this->dammiSquadrePerTorneo($torneoId);
        if(!empty($squadreGrezze)){
            foreach($squadreGrezze as $squadra){
                $compagno1 = $squadra->getidcompagnodestinatario();
                $compagno2 = $squadra->getidcompagnomittente();
                if($userId == $compagno1 || $userId == $compagno2){
                    return $squadra;
                }
            }
        }
        return null;
    }

    public function dammiCompagnoSquadra($userId, $torneoId): null | Accountgiocatori{
        if($this->HaSquadra($userId, $torneoId)){
            $squadra = $this->dammiSquadra($userId, $torneoId);
            $compagno1 = $squadra->getidcompagnodestinatario();
            $compagno2 = $squadra->getidcompagnomittente();
            $compagno = new Accountgiocatori();
            if($userId == $compagno1){
                $compagno->select(['idgiocatore' => $compagno2]);
            }elseif($userId == $compagno2){
                $compagno->select(['idgiocatore' => $compagno1]);
            }
            $compagno->setpassword(null);
            return $compagno;
        }else{
            return null;
        }
        
    }

    public function dammiMieiMittenti($userId, $torneoId): array{
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND iddestinatario = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId]);
        $richiesteGrezze = $sth->fetchAll();
        $mittenti = [];
        if(!empty($richiesteGrezze)){
            foreach($richiesteGrezze as $richiesta){
            $mittente = new Accountgiocatori();
            $mittente->select(['idgiocatore' => $richiesta['idmittente']]);
            $mittente->setpassword(null);
            $mittenti['richiesta'][] = [
                'mittente' => $mittente,
                'idrichiesta' => $richiesta['idrichiesta'],
                'stato' => $richiesta['stato']      
            ];
            }
            return $mittenti;
        }
        return [];
    }

    public function dammiMieiDestinatari($userId, $torneoId): array | null{
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND idmittente = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId]);
        $richiesteGrezze = $sth->fetchAll();
        $destinatari = [];
        if(!empty($richiesteGrezze)){
            foreach($richiesteGrezze as $richiesta){
            $destinatario = new Accountgiocatori();
            $destinatario->select(['idgiocatore' => $richiesta['iddestinatario']]);
            $destinatario->setpassword(null);
            $destinatari['richiesta'][] = [
                'destinatario' => $destinatario,
                'idrichiesta' => $richiesta['idrichiesta'],
                'stato' => $richiesta['stato']      
            ];
            }
            return $destinatari;
        }
        return null;
    }

    public function HaSquadra($userId, $torneoId): bool{
        $squadra = $this->dammiSquadra($userId, $torneoId);
        if(empty($squadra)){
            return false;
        }else{
            return true;
        }
    }

    //VECCHIE
    /*
    public function dammiIscrittiTranneMe($userId, $torneoId){
        $sql = "SELECT * FROM giocatoretorneo WHERE idtorneo = ? AND idgiocatore != ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId]);
        $compagniSingleGrezzi = $sth->fetchAll();
        $compagniSingle = [];
        foreach($compagniSingleGrezzi as $elemento){
            $compagnoSingle = new Accountgiocatori();
            $compagnoSingle->select($elemento['idgiocatore']);
            $compagniSingle[] = $compagnoSingle;
        }
        return $compagniSingle;
    }


    public function dammiDestinatari($userId, $torneoId){
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND idmittente = ? AND iddestinatario != ? AND stato = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, $userId, "in attesa"]);
        $richiesteInviateGrezze = $sth->fetchAll();
        $destinatari = [];
        foreach($richiesteInviateGrezze as $elemento){
            $destinatario = new Accountgiocatori();
            $destinatario->select($elemento['iddestinatario']);
            $destinatario->setpassword(null);
            $destinatari[] = [
                'account' => $destinatario,
                'idrichiesta' => $elemento['idrichiesta']
            ];
        }
        return $destinatari;
    }

    public function dammiMittenti($userId, $torneoId){
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND idmittente != ? AND iddestinatario = ? AND stato = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, $userId, "in attesa"]);
        $richiesteRicevuteGrezze = $sth->fetchAll();
        $mittenti = [];
        foreach($richiesteRicevuteGrezze as $elemento){
            $mittente = new Accountgiocatori();
            $mittente->select($elemento['idmittente']);
            $mittente->setpassword(null);
            $mittenti[] = [
                'account' => $mittente,
                'idrichiesta' => $elemento['idrichiesta']
            ];
        }
        return $mittenti;
    }

    public function dammiSquadraPerUserIdTorneoId($userId, $torneoId){
        $sql = "SELECT * FROM squadre WHERE idtorneo = ? AND ( idcompagnomittente = ? OR idcompagnodestinatario = ? )";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, $userId]);
        $squadraGrezzo = $sth->fetchAll();
        if(empty($squadraGrezzo)){
            return false;
        }
        $squadra = new Squadre();
        $squadra->select(['idsquadra' => $squadraGrezzo[0]['idsquadra']]);
        return $squadra;
    }

    public function dammiCompagnoSquadra($userId, $torneoId){
        $squadra = $this->dammiSquadraPerUserIdTorneoId($userId, $torneoId);
        if($squadra->getidcompagnomittente() == $userId){
            $compagnoId = $squadra->getidcompagnodestinatario();
        }else{
            $compagnoId = $squadra->getidcompagnomittente();
        }
        $compagno = new Accountgiocatori ();
        $compagno->select(['idgiocatore'=>$compagnoId]);
        $compagno->setpassword(null);
        return $compagno;
        
    }

    public function dammiSquadrePerTorneoId($torneoId){
        $sql = "SELECT * FROM squadre WHERE idtorneo = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId]);
        $squadreGrezze = $sth->fetchAll();
        if(empty($squadreGrezze)){
            return false;
        }
        $squadre = [];
        foreach($squadreGrezze as $elemento){
            $squadra = new Squadre();
            $squadra->select(['idsquadra' => $elemento['idsquadra']]);
            $giocatoreMittente = new Accountgiocatori();
            $giocatoreMittente->select(['idgiocatore' => $elemento['idcompagnomittente']]);
            $giocatoreDestinatario = new Accountgiocatori();
            $giocatoreDestinatario->select(['idgiocatore' => $elemento['idcompagnodestinatario']]);
            $squadre[] = [
                'squadra' => $squadra,
                'giocatoremittente' => $giocatoreMittente,
                'giocatoredestinatario' =>$giocatoreDestinatario
            ];
        }   
        return $squadre;
    }
    */
}