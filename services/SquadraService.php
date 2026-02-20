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

    public function ottieniRichiesteRicevute($user, $torneoId): array{
        $data = [];
        if($user->isGiocatore()){
            $userId = $user->getidgiocatore();
            $richiesteRicevute = $this->squadraRepository->dammiMieiMittenti($userId, $torneoId);
            //var_dump($richiesteRicevute);exit;
            foreach($richiesteRicevute as $item) {
                $mittente = $item['mittente'];
                $data[] = [
                    'idrichiesta'     => $item['idrichiesta'],
                    'idmittente'      => $mittente->getidgiocatore(),
                    'nomemittente'    => $mittente->getnome(),
                    'cognomemittente' => $mittente->getcognome()
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
                    'idrichiesta'     => $item['idrichiesta'],
                    'iddestinatrio'      => $destinatario->getidgiocatore(),
                    'nomedestinatario'    => $destinatario->getnome(),
                    'cognomedestinatario' => $destinatario->getcognome()
                ];
            }
        }
            return $data;
        }

        public function ottieniGiocatoriSingleNonMieiMittentiENonMieiDestinatari($user, $torneoId){
            $risultato = [];
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
                    if(!in_array($giocatore->getidgiocatore(), $idEsclusi)){
                        $giocatoriSingle[] = $giocatore;
                    }
                }
                foreach($giocatoriSingle as $giocatore){
                    $risultato[] = [
                    'idgiocatore' => $giocatore->getidgiocatore(),
                    'nome' => $giocatore->getnome(),
                    'cognome' => $giocatore->getcognome()
                    ];
                }
            }
            return $risultato;
        }
       
    }
    /*
    public function ottieniDatiMiaSquadra($userId, $torneoId) {
        $squadra = $this->squadraRepository->dammiSquadra($userId, $torneoId);
        $compagno = $this->squadraRepository->dammiCompagnoSquadra($userId, $torneoId);
        $mittenti = $this->squadraRepository->dammiMieiMittenti($userId, $torneoId);
        $destinatari = $this->squadraRepository->dammiMieiDestinatari($userId, $torneoId);
        $giocatoriSenzaDiMe = $this->utentiRepository->dammiGiocatoriIscrittiTorneo($torneoId, $userId);
        $filtrati = [];
            if(!empty($giocatoriSenzaDiMe)){
                foreach($giocatoriSenzaDiMe as $giocatore){
                    if(!in_array($giocatore, $mittenti) || !in_array($giocatore, $destinatari)){
                        $filtrati[] = $giocatore;
                    }
                }
            }
        return [
            'squadra' => $squadra,
            'compagno' => $compagno,
            'mittenti' => $mittenti,
            'destinatari' => $destinatari,
            'single' => $filtrati
        ];
    }

    public function ottieniTutteSquadrePerTorneo($torneoId){
        return $this->squadraRepository->dammiSquadrePerTorneo($torneoId);
    }

    public function ottieniTutteSquadre(){
        return $this->squadraRepository->dammiTutteSquadre();
    }

    */
