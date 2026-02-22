RELAZIONE TECNICA: OTTIMIZZAZIONE GEOGRAFICA E IMPATTO AMBIENTALE (BURRACO GREEN)
1. Logica dell'Algoritmo
L'algoritmo calcola la sede ottimale per ogni match basandosi sulla minimizzazione della distanza inquinante totale.
La formula applicata per ogni giocatore verso un determinato circolo è:

KM Inquinanti={ 
0
Distanza Reale
​	
  
se Distanza Reale≤Soglia Green
se Distanza Reale>Soglia Green
​	
 
2. Dataset di Riferimento
Circoli (Sedi disponibili)
ID	Indirizzo	Coordinate
5	Piazza del Duomo (Centro)	[45.4639, 9.1906]
6	Via delle Forze Armate (Ovest)	[45.4591, 9.1026]
Giocatori (Cluster geografici)
ID	Indirizzo	Coordinate	Soglia Green
G6	Via Gulli	[45.4652, 9.1332]	1500m
G7	Via Rembrandt	[45.4675, 9.1391]	1500m
G8	Via Scanini	[45.4660, 9.0911]	0m
G9	Via Valle Antrona	[45.4575, 9.0893]	0m
G10	Via Civitali	[45.4748, 9.1291]	0m
G11	Via Monreale	[45.4758, 9.1397]	0m
G12	Via Torino	[45.4617, 9.1856]	1000m
G13	Corso Venezia	[45.4676, 9.1980]	1000m
3. Analisi Incidenza "Soglia Green"
Evidenza dell'impatto della "Mobilità Dolce" sui conti individuali.

Test verso Circolo 6 (Ovest)
Giocatore	Distanza Reale	Soglia Green	Incide?	KM Inquinanti
G6	1.10 km	1.50 km	SÌ	0.00 km
G7	1.30 km	1.50 km	SÌ	0.00 km
G12	6.70 km	1.00 km	NO	6.70 km
Test verso Circolo 5 (Centro)
Giocatore	Distanza Reale	Soglia Green	Incide?	KM Inquinanti
G6	4.60 km	1.50 km	NO	4.60 km
G12	0.40 km	1.00 km	SÌ	0.00 km
G13	0.70 km	1.00 km	SÌ	0.00 km
4. Dettaglio Match: SCENARIO CON SOGLIA GREEN
A. Algoritmo Dinamico (Ottimizzato)
Match	Scontro	Circolo Scelto	Distanza (KM)
0	Squadra 2 vs 3	Circolo 6	9.35
1	Squadra 4 vs 5	Circolo 5	12.11
2	Squadra 2 vs 4	Circolo 6	9.68
3	Squadra 3 vs 5	Circolo 5	11.80
4	Squadra 2 vs 5	Circolo 5	9.42
5	Squadra 3 vs 4	Circolo 6	8.66
TOTALE			61.02 KM
B. Solo Circolo 6 (Fisso)
Match	Scontro	Circolo	Distanza (KM)
Totale Match 0-5	Varie Squadre	Circolo 6	83.47 KM
C. Solo Circolo 5 (Fisso)
Match	Scontro	Circolo	Distanza (KM)
Totale Match 0-5	Varie Squadre	Circolo 5	99.99 KM
5. Dettaglio Match: SCENARIO SENZA SOGLIA GREEN (Soglia 0)
D. Algoritmo Dinamico (Ottimizzato)
Match	Scontro	Circolo Scelto	Distanza (KM)
0	Squadra 2 vs 3	Circolo 6	9.35
1	Squadra 4 vs 5	Circolo 5	13.28
2	Squadra 2 vs 4	Circolo 6	9.68
3	Squadra 3 vs 5	Circolo 5	12.97
4	Squadra 2 vs 5	Circolo 5	10.59
5	Squadra 3 vs 4	Circolo 6	8.66
TOTALE			64.53 KM
E. Solo Circolo 6 (Fisso)
Match	Scontro	Circolo	Distanza (KM)
Totale Match 0-5	Varie Squadre	Circolo 6	83.47 KM
F. Solo Circolo 5 (Fisso)
Match	Scontro	Circolo	Distanza (KM)
Totale Match 0-5	Varie Squadre	Circolo 5	103.50 KM
6. Conclusioni Scientifiche
Configurazione	Con Soglia Green	Senza Soglia Green
Algoritmo Dinamico	61.02 km	64.53 km
Sede Fissa Peggiore	99.99 km	103.50 km
Risparmio Massimizzato: L'algoritmo garantisce una riduzione dell'impatto ambientale fino al 38% rispetto alla scelta di una sede fissa centrale (Circolo 5).
Efficacia Mobilità Dolce: La "Soglia Green" abbatte ulteriormente le emissioni di 3.51 km per giornata, premiando i giocatori che risiedono in prossimità dei circoli.
Analisi Cluster: Il sistema identifica automaticamente la densità geografica dei partecipanti, assegnando i match del cluster Ovest al Circolo 6 e i match con partecipanti del cluster Centro al Circolo 5.