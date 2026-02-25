<?php
class Algoritmo {
    public static function generaPartite($squadre, $circoli, $distanze) {
        $ids = array_map(fn($s) => $s->getidsquadra(), $squadre);
        if (count($ids) % 2 != 0) $ids[] = null;
        
        $n = count($ids);
        $partiteRR = [];

        // 1. Generazione Round Robin Completo (Andata e Ritorno)
        for ($r = 0; $r < ($n - 1) * 2; $r++) {
            $turno = [];
            for ($i = 0; $i < $n / 2; $i++) {
                $s1 = $ids[$i];
                $s2 = $ids[$n - 1 - $i];
                if ($s1 !== null && $s2 !== null) {
                    $turno[] = ($r % 2 == 0) ? [$s1, $s2] : [$s2, $s1];
                }
            }
            $partiteRR[] = $turno;
            $fixed = array_shift($ids);
            $last = array_pop($ids);
            array_unshift($ids, $fixed);
            array_splice($ids, 1, 0, [$last]);
        }

        $blocchiGiornate = array_chunk($partiteRR, 3);
        $risultato = [];
        $giornataId = 1;

        foreach ($blocchiGiornate as $giornataReale) {
            // 2. Identifichiamo le "Catene di Squadre"
            // In una giornata, se A gioca con B, e B gioca con C, 
            // A, B e C devono stare nello STESSO circolo.
            $gruppi = []; 
            foreach ($giornataReale as $turno) {
                foreach ($turno as $match) {
                    self::unisciGruppi($gruppi, $match[0], $match[1]);
                }
            }

            // 3. Per ogni gruppo di squadre, scegliamo il CIRCOLO MIGLIORE
            $sedeGruppo = [];
            foreach ($gruppi as $indice => $membri) {
                $migliorCircolo = null;
                $minDistanzaTotale = PHP_INT_MAX;

                foreach ($circoli as $c) {
                    $idC = $c->getidcircolo();
                    $distanzaCorrente = 0;
                    foreach ($membri as $idS) {
                        $distanzaCorrente += $distanze[$idS][$idC] ?? 999999;
                    }

                    if ($distanzaCorrente < $minDistanzaTotale) {
                        $minDistanzaTotale = $distanzaCorrente;
                        $migliorCircolo = $idC;
                    }
                }
                $sedeGruppo[$indice] = $migliorCircolo;
            }

            // 4. Assegnazione finale con vincolo assoluto
            foreach ($giornataReale as $indexTurno => $turno) {
                foreach ($turno as $match) {
                    $idGruppo = self::trovaGruppo($gruppi, $match[0]);
                    $idCircolo = $sedeGruppo[$idGruppo];

                    $risultato[] = [
                        'squadra1'   => $match[0],
                        'squadra2'   => $match[1],
                        'giornata'   => $giornataId,
                        'turno'      => $indexTurno + 1,
                        'id_circolo' => $idCircolo,
                        'km_totali'  => ($distanze[$match[0]][$idCircolo] ?? 0) + ($distanze[$match[1]][$idCircolo] ?? 0)
                    ];
                }
            }
            $giornataId++;
        }
        return $risultato;
    }

    // Funzioni helper per gestire i cluster di squadre nella giornata
    private static function unisciGruppi(&$gruppi, $s1, $s2) {
        $g1 = self::trovaGruppo($gruppi, $s1);
        $g2 = self::trovaGruppo($gruppi, $s2);

        if ($g1 === null && $g2 === null) {
            $gruppi[] = [$s1, $s2];
        } elseif ($g1 !== null && $g2 === null) {
            $gruppi[$g1][] = $s2;
        } elseif ($g1 === null && $g2 !== null) {
            $gruppi[$g2][] = $s1;
        } elseif ($g1 !== $g2) {
            $gruppi[$g1] = array_merge($gruppi[$g1], $gruppi[$g2]);
            unset($gruppi[$g2]);
        }
    }

    private static function trovaGruppo($gruppi, $s) {
        foreach ($gruppi as $i => $membri) {
            if (in_array($s, $membri)) return $i;
        }
        return null;
    }
}