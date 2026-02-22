<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Algoritmo{

    protected $squadraRepository;
    protected $torneoRepository;
    protected $utentiRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
        $this->utentiRepository = new UtentiRepository();
    }

    public static function generaPartite($squadre, $circoli, $distanze, $giornate) {
        $partiteDaAssegnare = [];
        
        for ($i = 0; $i < count($squadre); $i++) {
            for ($j = $i + 1; $j < count($squadre); $j++) {
                $partiteDaAssegnare[] = [
                    's1' => $squadre[$i]->getidsquadra(),
                    's2' => $squadre[$j]->getidsquadra()
                ];
            }
        }
        $calendarioFinale = [];
        $impegniSquadre = [];
        $turnoAttuale = 1;
        while (count($partiteDaAssegnare) > 0) {
            $partiteAssegnateInQuestoTurnoTotale = 0;
            foreach ($giornate as $g) {
                foreach ($partiteDaAssegnare as $chiave => $scontro) {
                    $s1 = $scontro['s1'];
                    $s2 = $scontro['s2'];
                    if (isset($impegniSquadre[$g][$turnoAttuale][$s1]) || 
                        isset($impegniSquadre[$g][$turnoAttuale][$s2])) {
                        continue;
                    }
                    $miglioreCircolo = null;
                    $minDist = PHP_INT_MAX;

                    foreach ($circoli as $circolo) {
                        $idC = $circolo->getidcircolo();
                        
                        $d1 = $distanze[$s1][$idC] ?? 999;
                        $d2 = $distanze[$s2][$idC] ?? 999;
                        $distanzaTotaleScontro = $d1 + $d2;

                        if ($distanzaTotaleScontro < $minDist) {
                            $minDist = $distanzaTotaleScontro;
                            $miglioreCircolo = $idC;
                        }
                    }

                    $impegniSquadre[$g][$turnoAttuale][$s1] = true;
                    $impegniSquadre[$g][$turnoAttuale][$s2] = true;
                    
                    $calendarioFinale[] = [
                        'squadra1'  => $s1,
                        'squadra2'  => $s2,
                        'giornata'  => $g,
                        'turno'     => $turnoAttuale,
                        'id_circolo' => $miglioreCircolo,
                        'km_totali' => $minDist 
                    ];

                    unset($partiteDaAssegnare[$chiave]);
                    $partiteAssegnateInQuestoTurnoTotale++;
                }
            }

            if ($partiteAssegnateInQuestoTurnoTotale == 0) {
                $turnoAttuale++;
            }

            if ($turnoAttuale > 100) break;
        }

        return $calendarioFinale;
    }
}




    