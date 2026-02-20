<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

Dammi stato torneo
Dammi tutti i tornei
Dammi torneo per torneoId
Dammi tornei per giocatoreId
Dammi tornei per circoloId
E' giocatore iscritto? per torneoId
E' circolo iscritto? per torneId
IscriviGiocatore per torneoId e userId

*/

class TorneoRepository{
    protected $database;

    public function __construct()
    {
        $this->database = Database::instance();
    }

    public function dammiStatoTorneo($torneoId): null | string{
        $torneo = new Tornei();
        $torneo->select(['idtorneo'=>$torneoId]);
        if(!empty($torneo)){
            return $torneo->getstatotorneo();
        }else{
            return null;
        }
    }

    public function dammiTuttiTornei(): array{
        $torneiGrezzi = $this->database->select("tornei", [], []);
        $tornei = [];
        if(!empty($torneiGrezzi)){
            foreach($torneiGrezzi as $elemento){
            $torneo = new Tornei();
            $torneo->select(['idtorneo' => $elemento['idtorneo']]);
            $tornei[] = $torneo;
            }
            return $tornei;
        }else{
            return [];
        }
    }

    public function dammiTorneo($torneoId): Tornei | null{
        $torneo = new Tornei();
        $torneo->select(['idtorneo'=>$torneoId]);
        if(!empty($torneo)){
            return $torneo;
        }else{
            return null;
        }
    }

    public function dammiTorneiDiGiocatore($userId): array{
        $torneiGrezzi = $this->database->select("giocatoretorneo", [], ['idgiocatore' => $userId]);
        $tornei = [];
        if(!empty($torneiGrezzi)){
            foreach($torneiGrezzi as $elemento){
            $torneo = new Tornei();
            $torneo->select(['idtorneo' => $elemento['idtorneo']]);
            $tornei[] = $torneo;
            }
            return $tornei;
        }
        return [];
    }

    public function dammiTorneiDiCircolo($userId): array{
        $torneiGrezzi = $this->database->select("circolotorneo", [], ['idcircolo' => $userId]);
        $tornei = [];
        if(!empty($torneiGrezzi)){
            foreach($torneiGrezzi as $elemento){
                $torneo = new Tornei();
                $torneo->select(['idtorneo' => $elemento['idtorneo']]);
                $tornei[] = $torneo;
            }
            return $tornei;
        }
        return [];
    }

    public function IsGiocatoreIscritto($torneoId, $userId): bool{
        $risultato = $this->database->select("giocatoretorneo", [], ['idgiocatore' => $userId, 'idtorneo' => $torneoId]);
        return !empty($risultato);
    }

    public function IsCircoloIscritto($torneoId, $userId): bool{
        $risultato = $this->database->select("circolotorneo", [], ['idcircolo' => $userId, 'idtorneo' => $torneoId]);
        return !empty($risultato);
    }

    //AZIONI

    public function iscriviGiocatore($torneoId, $userId): bool{
        if($this->IsGiocatoreIscritto($torneoId, $userId)){
            return false; 
        }
        $giocatoreTorneo = new Giocatoretorneo();
        $giocatoreTorneo->setidgiocatore($userId);
        $giocatoreTorneo->setidtorneo($torneoId);
        return (bool)$giocatoreTorneo->insert();
    }

    public function disiscriviGiocatore($torneoId, $userId): bool{
        if(!$this->IsGiocatoreIscritto($torneoId, $userId)){
            return false; 
        }
        $giocatoreTorneo = new Giocatoretorneo();
        $giocatoreTorneo->select(['idgiocatore' => $userId, 'idtorneo' => $torneoId]);
        return (bool)$giocatoreTorneo->delete();
    }

    public function iscriviCircolo($torneoId, $userId): bool {
        if ($this->IsCircoloIscritto($torneoId, $userId)) {
            UIMessage::setError(ALREADY_ENROLLED);
            return false;
        }
        $circoloTorneo = new Circolotorneo();
        $circoloTorneo->setidcircolo($userId);
        $circoloTorneo->setidtorneo($torneoId);
        return (bool)$circoloTorneo->insert();
    }

    public function disiscriviCircolo($torneoId, $userId): bool{
        if(!$this->IsCircoloIscritto($torneoId, $userId)){
            return false; 
        }
        $circoloTorneo = new Circolotorneo();
        $circoloTorneo->select(['idcircolo' => $userId, 'idtorneo' => $torneoId]);
        return (bool)$circoloTorneo->delete();
    }
    
}