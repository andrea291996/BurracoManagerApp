<?php

// 1. CONFIGURAZIONE
$squadre = ['S1', 'S2', 'S3'];
$circoli = ['C1', 'C2', 'C3'];
$giornate = [1, 2]; // Fisso a 2 giornate come da tua richiesta

// Mappa distanze
$distanze = [
    'S1' => ['C1' => 10, 'C2' => 30, 'C3' => 50],
    'S2' => ['C1' => 40, 'C2' => 10, 'C3' => 20],
    // ... popola le altre
];

// 2. GENERAZIONE SCONTRI (Tutti contro Tutti)
$partiteDaAssegnare = [];
for ($i = 0; $i < count($squadre); $i++) {
    for ($j = $i + 1; $j < count($squadre); $j++) {
        $partiteDaAssegnare[] = ['s1' => $squadre[$i], 's2' => $squadre[$j]];
    }
}

$totalePartiteIniziali = count($partiteDaAssegnare);
$calendarioFinale = [];
$impegniSquadre = [];
$numTurniNecessari = 0;

// 3. LOGICA DI ASSEGNAZIONE DINAMICA
// Continuiamo ad aggiungere turni finché ci sono partite da assegnare
while (count($partiteDaAssegnare) > 0) {
    $numTurniNecessari++;
    $partiteAssegnateInQuestoTurno = 0;

    foreach ($giornate as $g) {
        // Proviamo ad assegnare le partite rimaste in questo nuovo turno ($numTurniNecessari)
        foreach ($partiteDaAssegnare as $chiave => $scontro) {
            $s1 = $scontro['s1'];
            $s2 = $scontro['s2'];

            // Se le squadre sono già occupate in questa giornata/turno, saltiamo
            if (isset($impegniSquadre[$g][$numTurniNecessari][$s1]) || 
                isset($impegniSquadre[$g][$numTurniNecessari][$s2])) {
                continue;
            }

            $miglioreCircolo = null;
            $minDist = PHP_INT_MAX;

            // Cerchiamo il circolo più vicino per questo scontro
            foreach ($circoli as $c) {
                $d = ($distanze[$s1][$c] ?? 50) + ($distanze[$s2][$c] ?? 50);
                if ($d < $minDist) {
                    $minDist = $d;
                    $miglioreCircolo = $c;
                }
            }

            // Assegniamo la partita
            $impegniSquadre[$g][$numTurniNecessari][$s1] = true;
            $impegniSquadre[$g][$numTurniNecessari][$s2] = true;
            
            $calendarioFinale[] = [
                'partita' => "$s1 vs $s2",
                'g' => $g,
                't' => $numTurniNecessari,
                'c' => $miglioreCircolo,
                'km' => $minDist
            ];

            // Rimuoviamo la partita dalla lista di quelle da fare
            unset($partiteDaAssegnare[$chiave]);
            $partiteAssegnateInQuestoTurno++;
        }
    }

    // Sicurezza: se in un intero ciclo di turni/giornate non riusciamo ad assegnare nulla, 
    // significa che i vincoli sono impossibili.
    if ($numTurniNecessari > 100) break; 
}

// 4. OUTPUT
echo "=== RISULTATO ANALISI ===\n";
echo "Per completare tutte le $totalePartiteIniziali partite in " . count($giornate) . " giornate:\n";
echo "IL NUMERO MINIMO DI TURNI NECESSARI È: " . $numTurniNecessari . "\n\n";

echo "--- DETTAGLIO CALENDARIO ---\n";
foreach ($calendarioFinale as $p) {
    echo "Giorno {$p['g']} | Turno {$p['t']} | {$p['partita']} @ {$p['c']} ({$p['km']} km)\n";
}