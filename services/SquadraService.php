<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*

ottieni dati mia squadra (squadra, compagno, mittenti, destinatari, single) per userId e per TorneoId
    ottieni richieste ricevute
    ottieni richieste inviate
    ottieni compagni single
    ottieni compagno squadra
ottieni tutte squadre per torneoId
ottieni tutte squadre

*/

class SquadraService{

    protected $squadraRepository;
    protected $torneoRepository;
    protected $utentiRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
        $this->utentiRepository = new UtentiRepository();
    }

    public function HaSquadra($user, $torneoId){
        return $this->squadraRepository->HaSquadra($user->getidgiocatore(), $torneoId);
    }

    public function ottieniSquadra($user, $torneoId): array{
        if($user->isGiocatore() && $this->squadraRepository->HaSquadra($user->getidgiocatore(), $torneoId)){
                $compagno = $this->squadraRepository->dammiCompagnoSquadra($user->getidgiocatore(), $torneoId);
                $data[] = [
                    'idcompagno' => $compagno->getidgiocatore(),
                    'nome'       => $compagno->getnome(),
                    'cognome'    => $compagno->getcognome()
                ];
            return $data;
        }else{
            return [];
        }
    }

    public function ottieniRichiesteRicevute($user, $torneoId): array{
        $data = [];
        if($user->isGiocatore()){
            $userId = $user->getidgiocatore();
            $richiesteRicevute = $this->squadraRepository->dammiMieiMittenti($userId, $torneoId);
            foreach($richiesteRicevute as $item) {
                $mittente = $item['mittente'];
                $data[] = [
                    'idrichiesta'     => $item['idrichiesta'],
                    'idmittente'      => $mittente->getidgiocatore(),
                    'nomemittente'    => $mittente->getnome(),
                    'cognomemittente' => $mittente->getcognome(),
                    'idtorneo'        => $torneoId
                ];
            }
        }
            return $data;
        }

        public function ottieniRichiesteInviate($user, $torneoId): array{
        $data = [];
        if($user->isGiocatore()){
            $userId = $user->getidgiocatore();
            $richiesteInviate = $this->squadraRepository->dammiMieiDestinatari($userId, $torneoId);
            foreach($richiesteInviate as $item) {
                $destinatario = $item['destinatario'];
                $data[] = [
                    'idrichiesta'         => $item['idrichiesta'],
                    'iddestinatrio'       => $destinatario->getidgiocatore(),
                    'nomedestinatario'    => $destinatario->getnome(),
                    'cognomedestinatario' => $destinatario->getcognome(),
                    'idtorneo'            => $torneoId
                ];
            }
        }
            return $data;
        }

        public function ottieniGiocatoriSingleNonMieiMittentiENonMieiDestinatari($user, $torneoId){
            $data = [];
            if($user->isGiocatore()){
                $userId = $user->getidgiocatore();
                $richiesteInviate = $this->squadraRepository->dammiMieiDestinatari($userId, $torneoId);
                $richiesteRicevute = $this->squadraRepository->dammiMieiMittenti($userId, $torneoId);
                $giocatoriIscritti = $this->utentiRepository->dammiGiocatoriIscrittiTorneo($torneoId, $userId);
                $idEsclusi = [];
                $giocatoriSingle = [];
                foreach($richiesteInviate as $item){
                    $idEsclusi[] = $item['destinatario']->getidgiocatore();
                }
                foreach($richiesteRicevute as $item){
                    $idEsclusi[] = $item['mittente']->getidgiocatore();
                }
                foreach($giocatoriIscritti as $giocatore){
                    if(!in_array($giocatore->getidgiocatore(), $idEsclusi) && !$this->squadraRepository->HaSquadra($giocatore->getidgiocatore(), $torneoId)){
                        $giocatoriSingle[] = $giocatore;
                    }
                }
                foreach($giocatoriSingle as $giocatore){
                    $data[] = [
                    'idgiocatore' => $giocatore->getidgiocatore(),
                    'nome'        => $giocatore->getnome(),
                    'cognome'     => $giocatore->getcognome(),
                    'idtorneo'    => $torneoId
                    ];
                }
            }
            return $data;
        }
       
        //POST

        public function inviaRichiesta($idTorneo, $idMittente, $idDestinatario): bool{
            return $this->squadraRepository->inserisciRichiesta($idTorneo, $idMittente, $idDestinatario);
        }

        public function annullaRichiesta($idRichiesta): bool{
            return $this->squadraRepository->annullaRichiesta($idRichiesta);
        }

        public function rifiutaRichiesta($idRichiesta): bool{
            return $this->squadraRepository->rifiutaRichiesta($idRichiesta);
        }

        public function accettaRichiesta($idRichiesta): bool{
            return $this->squadraRepository->accettaRichiesta($idRichiesta);
        }

    }
    