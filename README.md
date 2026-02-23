# 🃏 Burraco Manager App

Web application per la gestione di tornei di burraco con formazione squadre, generazione partite e validazione distribuita dei punteggi.

---

## Tecnologie utilizzate

- **PHP**
- **Slim Framework**
- **Mustache** (Template Engine)
- **Pattern MVC (Model – View – Controller)**
- Database relazionale (MySQL / MariaDB)

---

## Architettura

L'applicazione è strutturata secondo il pattern **MVC**:

- **Model**  
  Gestione entità di dominio (Utente, Torneo, Squadra, Partita, Iscrizione) e accesso al database.

- **View**  
  Template Mustache per separare completamente la logica dalla presentazione.

- **Controller**  
  Gestione routing, orchestrazione della logica applicativa e interazione con i servizi di dominio.

Slim viene utilizzato per il routing HTTP e la gestione middleware (sessioni, autenticazione, autorizzazione).

---

## Ruoli Utente

### Giocatore
- Registrazione e autenticazione
- Iscrizione / disiscrizione ai tornei
- Richiesta di formazione squadra con altri giocatori
- Inserimento punteggi partita
- Visualizzazione dei propri tornei

### Circolo
- Visualizzazione tornei
- Partecipazione ai tornei
- Gestione partite assegnate

### Amministratore
- Creazione tornei
- Avvio torneo
- Generazione automatica delle partite
- Supervisione e validazione risultati

---

## Funzionalità Attualmente Implementate

### Autenticazione
- Registrazione utenti
- Login
- Gestione sessioni

### Gestione Tornei
- Visualizzazione lista tornei
- Filtri per tipologia e stato
- Pagina “I miei tornei”
- Iscrizione / disiscrizione dinamica

### Area Utente Anonimo
- Visualizzazione tornei
- Interfaccia strutturata (Info, Classifica, Calendario in sviluppo)

### Formazione Squadre
- Gestione richieste di squadra tra giocatori
- Accettazione / rifiuto richieste
- Creazione entità Squadra collegata al torneo

### Creazione Torneo (Admin)
- Inserimento parametri torneo:
  - Nome
  - Stato iniziale

### ▶ Avvio Torneo
- Cambio stato torneo da parte dell’amministratore
- Generazione automatica delle partite

### Algoritmo di Assegnazione Partite
Implementazione algoritmo per:

- Assegnare le partite ai circoli
- Minimizzare la distanza geografica tra squadre
- Ridurre l’impatto ambientale (logica di ottimizzazione)

---

## Funzionalità in Sviluppo

### Gestione e Validazione Punteggi
- Ogni squadra inserisce il punteggio della partita
- Sistema di validazione:
  - Il risultato viene salvato solo se tutte le squadre coinvolte inseriscono lo stesso punteggio
  - In caso di discrepanza → stato "in verifica"

---

## Logica di Dominio

Il progetto gestisce:

- Relazioni molti-a-molti (Utente ↔ Torneo)
- Entità aggregate (Torneo → Squadre → Partite)
- Transizioni di stato (CREATO → APERTO → IN_CORSO → CONCLUSO)
- Validazioni applicative lato service
- Controlli di autorizzazione per ruolo

---
