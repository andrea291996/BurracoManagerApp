<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class PartiteService{

    protected $utentiRepository;
    protected $squadraRepository;
    protected $partiteRepository;

    public function __construct()
    {
        $this->utentiRepository = new UtentiRepository;
        $this->squadraRepository = new SquadraRepository;
        $this->partiteRepository = new PartiteRepository;
    }

    public function inserisciPartite($partite, $idTorneo){
        foreach($partite as $partita){
            $this->partiteRepository->inserisciPartita($partita, $idTorneo);
        }
    }

    public function ottieniPartite($user, $idTorneo): array{
        $data = [];
        $partite = $this->partiteRepository->dammiPartitePerTorneo($idTorneo);
        if($user->isGiocatore()){
            $miasquadra = $this->squadraRepository->dammiSquadra($user->getidgiocatore(), $idTorneo);
            $idMiaSquadra = $miasquadra->getidsquadra();
            foreach($partite as $partita){
                if($partita->getidsquadra1() == $idMiaSquadra || $partita->getidsquadra2() == $idMiaSquadra){
                    $idsquadra1 = $partita->getidsquadra1();  
                    $idsquadra2 = $partita->getidsquadra2();
                    if($idMiaSquadra == $idsquadra1){
                        $squadraavversaria = $this->squadraRepository->dammiSquadraPerSquadraId($idsquadra2);
                        $idsquadraavversaria = $idsquadra2;
                    }else{
                        $squadraavversaria = $this->squadraRepository->dammiSquadraPerSquadraId($idsquadra1);        
                        $idsquadraavversaria = $idsquadra1;               
                    }
                    $compagnoavversario1 = $this->utentiRepository->dammiGiocatore($squadraavversaria->getidcompagnomittente());
                    $compagnoavversario2 = $this->utentiRepository->dammiGiocatore($squadraavversaria->getidcompagnodestinatario());
                    $compagno1 = $this->utentiRepository->dammiGiocatore($miasquadra->getidcompagnomittente());
                    $compagno2 = $this->utentiRepository->dammiGiocatore($miasquadra->getidcompagnodestinatario());
                    $circolo = $this->utentiRepository->dammiCircoloPerId($partita->getidcircolo());
                    $giornata = $partita->getgiornata();
                    $turno = $partita->getturno();
                    $haInserito = $this->partiteRepository->HaInseritoPunteggio($user->getidgiocatore(), $partita->getidpartita());
                    $èValidato = $this->partiteRepository->esistePunteggioPartita($partita->getidpartita());
                    $data[] = [
                        'idpartita' => $partita->getidpartita(),
                        'idmiasquadra'=> $idMiaSquadra,
                        'idsquadraavversaria' => $idsquadraavversaria,
                        'giornata' => $giornata,
                        'turno' => $turno,
                        'circolo' => $circolo->getnome(),
                        'miasquadra' => $miasquadra,
                        'nome_compagno1' => $compagno1->getnome(), 
                        'cognome_compagno1' => $compagno1->getcognome(),
                        'nome_compagno2' => $compagno2->getnome(),
                        'cognome_compagno2' => $compagno2->getcognome(),
                        'nome_avversario1' => $compagnoavversario1->getnome(), 
                        'cognome_avversario1' => $compagnoavversario1->getcognome(),
                        'nome_avversario2' => $compagnoavversario2->getnome(),
                        'cognome_avversario2' => $compagnoavversario2->getcognome(),
                        'stato_da_inserire' => !$haInserito && !$èValidato,
                        'stato_in_attesa'   => $haInserito && !$èValidato,
                        'stato_validato'    => $èValidato,
                        'mostra_form_inserimento' => !$haInserito && !$èValidato,                        
                        'punteggiomiasquadra' => $this->partiteRepository->dammiPunteggioSquadraPerPartita($idMiaSquadra, $partita->getidpartita()),
                        'punteggiosquadraavversaria' => $this->partiteRepository->dammiPunteggioSquadraPerPartita($idsquadraavversaria, $partita->getidpartita())
                    ];
                }
            }
        }else{
            foreach($partite as $partita){
                    $circolo = $this->utentiRepository->dammiCircoloPerId($partita->getidcircolo());
                    $giornata = $partita->getgiornata();
                    $turno = $partita->getturno();
                    $squadra1 = $this->squadraRepository->dammiSquadraPerSquadraId($partita->getidsquadra1());
                    $squadra2 = $this->squadraRepository->dammiSquadraPerSquadraId($partita->getidsquadra2());
                    $compagno1squadra1 = $this->utentiRepository->dammiGiocatore($squadra1->getidcompagnomittente());
                    $compagno2squadra1 = $this->utentiRepository->dammiGiocatore($squadra1->getidcompagnodestinatario());
                    $compagno1squadra2 = $this->utentiRepository->dammiGiocatore($squadra2->getidcompagnomittente());
                    $compagno2squadra2 = $this->utentiRepository->dammiGiocatore($squadra2->getidcompagnodestinatario());
                    $data[] = [
                        'giornata' => $giornata,
                        'turno' => $turno,
                        'circolo' => $circolo->getnome(),
                        'nome_compagno1_squadra1' => $compagno1squadra1->getnome(), 
                        'cognome_compagno1_squadra1' => $compagno1squadra1->getcognome(),
                        'nome_compagno2_squadra1' => $compagno2squadra1->getnome(),
                        'cognome_compagno2_squadra1' => $compagno2squadra1->getcognome(),
                        'nome_compagno1_squadra2' => $compagno1squadra2->getnome(), 
                        'cognome_compagno1_squadra2' => $compagno1squadra2->getcognome(),
                        'nome_compagno2_squadra2' => $compagno2squadra2->getnome(),
                        'cognome_compagno2_squadra2' => $compagno2squadra2->getcognome(),
                        'punteggiosquadra1' => $this->partiteRepository->dammiPunteggioSquadraPerPartita($partita->getidsquadra1(), $partita->getidpartita()),
                        'punteggiosquadra2' => $this->partiteRepository->dammiPunteggioSquadraPerPartita($partita->getidsquadra2(), $partita->getidpartita()),
                    ];
                }
        }
        return $data;
    }

    
}