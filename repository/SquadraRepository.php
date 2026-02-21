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
Inserisci richiesta
Annulla richiesta inviata
Accetta richiesta ricevuta (formazione squadra)
Rifiuta richiesta ricevuta

*/

class SquadraRepository{
    protected $database;
    protected $utentiRepository;

    public function __construct()
    {
        $this->database = Database::instance();
        $this->utentiRepository = new UtentiRepository;
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
        $sql = "SELECT * FROM squadre WHERE idtorneo = ? ";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId]);
        $squadreGrezze = $sth->fetchAll();
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
        if(!$this->HaSquadra($userId, $torneoId)){
            return null;
        }
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
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND iddestinatario = ? AND stato = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, STATUS_REQUEST_PENDING]);
        $richiesteGrezze = $sth->fetchAll();
        $mittenti = [];
        if(!empty($richiesteGrezze)){
            foreach($richiesteGrezze as $richiesta){
            $mittente = new Accountgiocatori();
            $mittente->select(['idgiocatore' => $richiesta['idmittente']]);
            $mittente->setpassword(null);
            $mittenti[] = [
                'mittente' => $mittente,
                'idrichiesta' => $richiesta['idrichiesta']     
            ];
            }
            return $mittenti;
        }
        return [];
    }

    public function dammiMieiDestinatari($userId, $torneoId): array{
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND idmittente = ? AND stato = ?";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, STATUS_REQUEST_PENDING]);
        $richiesteGrezze = $sth->fetchAll();
        $destinatari = [];
        if(!empty($richiesteGrezze)){
            foreach($richiesteGrezze as $richiesta){
            $destinatario = new Accountgiocatori();
            $destinatario->select(['idgiocatore' => $richiesta['iddestinatario']]);
            $destinatario->setpassword(null);
            $destinatari[] = [
                'destinatario' => $destinatario,
                'idrichiesta' => $richiesta['idrichiesta']     
            ];
            }
            return $destinatari;
        }
        return [];
    }

    public function HaSquadra($userId, $torneoId): bool{
        $sql = "SELECT * FROM squadre WHERE idtorneo = ? AND (idcompagnodestinatario = ? OR idcompagnomittente = ?)";
        $sth = $this->database->prepare($sql);
        $sth->execute([$torneoId, $userId, $userId]);
        $risultato = $sth->fetchAll();
        if(!empty($risultato)){
            return true;
        }else{
            return false;
        }
    }

    public function dammiGiocatoriSenzaSquadra($torneoId): array{
        $iscritti = $this->utentiRepository->dammiGiocatoriIscrittiTorneo($torneoId);
        $risultato = [];
        foreach($iscritti as $giocatore){
            if(!$this->HaSquadra($giocatore->getidgiocatore(), $torneoId)){
                $risultato[] = $giocatore;
            }
        }
        return $risultato;
    }

    public function inserisciRichiesta($idTorneo, $idMittente, $idDestinatario): bool{
        $richiesta = new Richieste();
        $richiesta->setidtorneo($idTorneo);
        $richiesta->setidmittente($idMittente);
        $richiesta->setiddestinatario($idDestinatario);
        $dataInvio = date('Y-m-d H:i:s');
        $richiesta->setdatainvio($dataInvio);
        $richiesta->setstato(STATUS_REQUEST_PENDING);
        $risultato = $richiesta->insert();
        if($risultato > 0){
            return true;
        }else{
            return false;
        }
    }

    public function annullaRichiesta($idRichiesta){
        $richiesta = new Richieste();
        $richiesta->select(['idrichiesta'=>$idRichiesta]);
        $dataFine = date('Y-m-d H:i:s');
        $richiesta->setdatafine($dataFine);
        $richiesta->setstato(STATUS_REQUEST_CANCELLED);
        $risultato = $richiesta->update();
        if($risultato > 0){
            return true;
        }else{
            return false;
        }
    }

    public function rifiutaRichiesta($idRichiesta){
        $richiesta = new Richieste();
        $richiesta->select(['idrichiesta'=>$idRichiesta]);
        $dataFine = date('Y-m-d H:i:s');
        $richiesta->setdatafine($dataFine);
        $richiesta->setstato(STATUS_REQUEST_REJECTED);
        $risultato = $richiesta->update();
        if($risultato > 0){
            return true;
        }else{
            return false;
        }
    }

    public function accettaRichiesta($idRichiesta){
        $richiesta = new Richieste();
        $richiesta->select(['idrichiesta'=>$idRichiesta]);
        $dataFine = date('Y-m-d H:i:s');
        $richiesta->setdatafine($dataFine);
        $richiesta->setstato(STATUS_REQUEST_ACCEPTED);
        $risultato = $richiesta->update();
        if($risultato > 0 && $this->inserisciSquadra($idRichiesta)){
            return true;
        }else{
            return false;
        }
    }

    public function inserisciSquadra($idRichiesta){
        $richiesta = new Richieste();
        $richiesta->select(['idrichiesta'=>$idRichiesta]);
        $idMittente = $richiesta->getidmittente();
        $idDestinatario = $richiesta->getiddestinatario();
        $idTorneo = $richiesta->getidtorneo();
        $statoRichiesta = $richiesta->getstato();
        //controllo se il torneo è aperto alle iscrizioni
        $torneo = new Tornei();
        $torneo->select(['idtorneo' => $idTorneo]);
        $statoTorneo = $torneo->getstatotorneo();
        if($statoTorneo == STATUS_TOURNAMENT_OPEN && $statoRichiesta == STATUS_REQUEST_ACCEPTED){
            $squadra = new Squadre();
            $squadra->setidtorneo($idTorneo);
            $squadra->setidcompagnomittente($idMittente);
            $squadra->setidcompagnodestinatario($idDestinatario);
            //annullo le altre richieste che avevano i giocatori
            $this->chiudiRichiestePendenti($idMittente, $idTorneo);
            $this->chiudiRichiestePendenti($idDestinatario, $idTorneo);
            return $squadra->insert();
        }else{
            return false;
        }
    }

    public function chiudiRichiestePendenti($idGiocatore, $idTorneo){
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND stato = ? AND (iddestinatario = ? OR idmittente = ?)";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idTorneo, STATUS_REQUEST_PENDING, $idGiocatore, $idGiocatore]);
        $richiestePendenti = $sth->fetchAll();
        foreach($richiestePendenti as $r){
            $richiesta = new Richieste();
            $richiesta->select(['idrichiesta' => $r['idrichiesta']]);
            $richiesta->setstato(STATUS_REQUEST_CANCELLED);
            $dataFine = date('Y-m-d H:i:s');
            $richiesta->setdatafine($dataFine);
            $richiesta->update();
        }
    }

    public function annullaRichiestaAccettata($idGiocatore, $idTorneo){
        $sql = "SELECT * FROM richieste WHERE idtorneo = ? AND stato = ? AND (iddestinatario = ? OR idmittente = ?)";
        $sth = $this->database->prepare($sql);
        $sth->execute([$idTorneo, STATUS_REQUEST_ACCEPTED, $idGiocatore, $idGiocatore]);
        $richiestaAccettaGrezza = $sth->fetch();
        $richiesta = new Richieste();
        $richiesta->select(['idrichiesta'=>$richiestaAccettaGrezza['idrichiesta']]);
        $dataFine = date('Y-m-d H:i:s');
        $richiesta->setdatafine($dataFine);
        $richiesta->setstato(STATUS_REQUEST_CANCELLED);
        $richiesta->update();
    }
}