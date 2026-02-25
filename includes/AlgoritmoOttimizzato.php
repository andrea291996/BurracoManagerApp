<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

class AlgoritmoOttimizzato {
    private $squadre;
    private $circoli;
    private $distanze;
    private $tuttiMatch = [];
    private $migliorDistanza = PHP_INT_MAX;
    private $miglioreSoluzione = null;
    private $csvHandle;
    private $contatoreSoluzioni = 0;
    private $matchPerTurno;
    private $idTorneo;

    public function __construct($squadre, $circoli, $distanze, $idTorneo) {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $this->squadre = $squadre;
        $this->circoli = $circoli;
        $this->distanze = $distanze;
        $this->idTorneo = $idTorneo;

        $ids = array_map(fn($s) => $s->getidsquadra(), $squadre);
        foreach ($ids as $s1) {
            foreach ($ids as $s2) {
                if ($s1 !== $s2) {
                    $this->tuttiMatch[] = ['s1' => $s1, 's2' => $s2, 'id' => "$s1-vs-$s2"];
                }
            }
        }

        $this->matchPerTurno = floor(count($this->squadre) / 2);
        $this->csvHandle = fopen("combinazioni_torneo_{$idTorneo}.csv", 'w');
        fputcsv($this->csvHandle, ['ID_Combinazione', 'Km_Totali_Torneo', 'Dettagli_Partite_JSON']);
    }

    public function esegui() {
        $this->backtrack([], $this->tuttiMatch, 0);
        fclose($this->csvHandle);
        return $this->miglioreSoluzione;
    }

    private function backtrack($calendario, $matchRimanenti, $distanzaAccumulata) {
        if ($distanzaAccumulata >= $this->migliorDistanza) {
            return;
        }
        if (empty($matchRimanenti)) {
            $this->salvaSoluzione($calendario, $distanzaAccumulata);
            return;
        }
        $prossimoIndice = count($calendario);
        $giornata = (int)($prossimoIndice / ($this->matchPerTurno * 2)) + 1;
        $turno = ((int)($prossimoIndice / $this->matchPerTurno) % 2) + 1;
        foreach ($matchRimanenti as $index => $match) {
            if ($this->squadraImpegnataInTurno($match, $calendario, $giornata, $turno)) continue;
            if ($this->isRitornoOggi($match, $calendario, $giornata)) continue;
            foreach ($this->circoli as $circolo) {
                $idC = $circolo->getidcircolo();

                if (!$this->isCircoloCoerente($match, $idC, $calendario, $giornata)) continue;
                $kmPartita = $this->calcolaCostoMatchSingolo($match, $idC, $calendario, $giornata);
                $nuovoMatch = [
                    'squadra1'   => $match['s1'],
                    'squadra2'   => $match['s2'],
                    'giornata'   => $giornata,
                    'turno'      => $turno,
                    'id_circolo' => $idC,
                    'km_totali'  => $kmPartita 
                ];
                $prossimiRimanenti = $matchRimanenti;
                unset($prossimiRimanenti[$index]);
                $this->backtrack(
                    array_merge($calendario, [$nuovoMatch]), 
                    array_values($prossimiRimanenti),
                    $distanzaAccumulata + $kmPartita
                );
            }
        }
    }

    private function calcolaCostoMatchSingolo($m, $idC, $cal, $g) {
        $costo = 0;
        $s1_gia_presente = false;
        $s2_gia_presente = false;

        foreach ($cal as $partitaPrecedente) {
            if ($partitaPrecedente['giornata'] == $g) {
                if ($partitaPrecedente['squadra1'] == $m['s1'] || $partitaPrecedente['squadra2'] == $m['s1']) $s1_gia_presente = true;
                if ($partitaPrecedente['squadra1'] == $m['s2'] || $partitaPrecedente['squadra2'] == $m['s2']) $s2_gia_presente = true;
            }
        }
        if (!$s1_gia_presente) $costo += ($this->distanze[$m['s1']][$idC] ?? 0);
        if (!$s2_gia_presente) $costo += ($this->distanze[$m['s2']][$idC] ?? 0);
        return $costo;
    }

    private function squadraImpegnataInTurno($m, $cal, $g, $t) {
        foreach ($cal as $match) {
            if ($match['giornata'] == $g && $match['turno'] == $t) {
                if (in_array($m['s1'], [$match['squadra1'], $match['squadra2']]) ||
                    in_array($m['s2'], [$match['squadra1'], $match['squadra2']])) return true;
            }
        }
        return false;
    }

    private function isRitornoOggi($m, $cal, $g) {
        foreach ($cal as $match) {
            if ($match['giornata'] == $g) {
                if ($match['squadra1'] == $m['s2'] && $match['squadra2'] == $m['s1']) return true;
            }
        }
        return false;
    }

    private function isCircoloCoerente($m, $idC, $cal, $g) {
        foreach ($cal as $match) {
            if ($match['giornata'] == $g) {
                $coinvolte = [$match['squadra1'], $match['squadra2']];
                if ((in_array($m['s1'], $coinvolte) || in_array($m['s2'], $coinvolte)) && $match['id_circolo'] != $idC) {
                    return false;
                }
            }
        }
        return true;
    }

    private function salvaSoluzione($calendario, $distanzaTotale) {
        $this->contatoreSoluzioni++;
        
        fputcsv($this->csvHandle, [$this->contatoreSoluzioni, $distanzaTotale, json_encode($calendario)]);

        if ($distanzaTotale < $this->migliorDistanza) {
            $this->migliorDistanza = $distanzaTotale;
            $this->miglioreSoluzione = $calendario;
        }
    }
}