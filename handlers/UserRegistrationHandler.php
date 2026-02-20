<?php

class UserRegistrationHandler
{
    protected $last_errors;

    function __construct(){
        $this->last_errors=[];
    }

    /**
     * Recupera il profilo completo basandosi sull'utente loggato
     */
    function profile($id, $tipo){
        $user = null;
        
        // Scegliamo la classe corretta in base alla tipologia salvata in sessione
        switch ($tipo) {
            case 'giocatore':       $user = new Accountgiocatori(); break;
            case 'circolo':         $user = new Accountcircoli(); break;
            case 'amministratore':  $user = new Accountamministratori(); break;
        }

        if($user && $user->select($id)){
            $user->setpassword(null); // Mai restituire la password
            return $user;
        }
        return null;
    }

    /**
     * Gestisce la registrazione (principalmente per i giocatori)
     */
    function registration(array $data) {
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $tipo = $data['tipologia']; 
        $dataAccount = [];
        $dataProfilo = [];

        // 1. Validazioni base
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->last_errors[] = "Email non valida";
            return false;
        }

        if (empty($password) || strlen($password) < 3) {
            $this->last_errors[] = "La password deve essere di almeno 6 caratteri";
            return false;
        }

        // 2. Controllo Email duplicata su TUTTE le tabelle
        if ($this->emailExists($email)) {
            $this->last_errors[] = "Questa email è già registrata nel sistema";
            return false;
        }

        // 3. Cifratura password
        $data['password'] = self::encryptPassword($password);

        // 4. Scelta della classe in base alla tipologia
        $account = null;
        if ($tipo === 'giocatore') {
            $account = new Accountgiocatori();
            $profilo = new Profiligiocatori();
            $dataAccount['email'] = $data['email'];
            $dataAccount['password'] = $data['password'];
            $dataAccount['nome'] = $data['nome'];
            $dataAccount['cognome'] = $data['cognome'];
            $dataProfilo['indirizzo'] = $data['indirizzo'];
            $dataProfilo['distanzanoninquinanteinmetri'] = $data['distanzamassima'];
        } elseif ($tipo === 'circolo') {
            $account = new Accountcircoli();
            $profilo = new Profilicircoli();
            $dataAccount['email'] = $data['email'];
            $dataAccount['password'] = $data['password'];
            $dataAccount['nome'] = $data['nome'];
            $dataProfilo['indirizzo'] = $data['indirizzo'];
        } else {
            $this->last_errors[] = "Tipologia di account non valida";
            return false;
        }

        // 5. Copia dei dati e inserimento

        $account->copy($dataAccount);
        $newIdAccount = $account->insert();
        $dataProfilo['idaccount'.$tipo] = $newIdAccount;
        $profilo->copy($dataProfilo);
        $newIdProfilo = $profilo->insert();
        
        if ($newIdAccount && $newIdProfilo) {
            return true;
        }

        $this->last_errors[] = "Errore tecnico durante la registrazione";
        return false;
}

    /**
     * Controlla se l'email esiste già in qualsiasi tabella account
     */
    private function emailExists($email){
        $tables = [new Accountgiocatori(), new Accountcircoli(), new Accountamministratori()];
        foreach($tables as $t){
            if($t->select(['email' => $email])) return true;
        }
        return false;
    }

    function getLastError(){
        return implode(" - ", $this->last_errors);
    }

    /**
     * Sistema di cifratura password
     */
    static function encryptPassword($password){
        $salt = "asd"; 
        $pepper = "23dfv";
        return md5($salt.$password.$pepper);
    }
}