<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserAccessHandler{
    protected $last_errors;

    function __construct(){
        $this->last_errors=[];
    }

    function login($email, $password){
        if(empty($password) || empty($email)){
            $this->last_errors[] = "Email e password obbligatorie";
            return false;
        }

        $enc = UserRegistrationHandler::encryptPassword($password);
        
        // 1. Tentativo come Giocatore
        $g = new Accountgiocatori();
        if($g->select(['email' => $email, 'password' => $enc])){
            $this->setSession('giocatore', $g->getidgiocatore());
            return true;
        }

        // 2. Tentativo come Circolo
        $c = new Accountcircoli();
        if($c->select(['email' => $email, 'password' => $enc])){
            $this->setSession('circolo', $c->getidcircolo());
            return true;
        }

        // 3. Tentativo come Amministratore
        $a = new Accountamministratori();
        if($a->select(['email' => $email, 'password' => $enc])){
            $this->setSession('amministratore', $a->getidamministratore());
            return true;
        }

        $this->last_errors[] = "Credenziali errate";
        return false;
    }

    private function setSession($tipo, $id){
        $_SESSION['account'] = [
            'id' => $id,
            'tipologia' => $tipo
        ];
    }

    function logout(){
        unset($_SESSION['account']);
        session_destroy();
        session_start();
    }

    function getLastError(){
        return implode(" - ",$this->last_errors);
    }

    static function isLogged(){
        return isset($_SESSION['account']);
    }

    static function getCurrentUser(){
        return isset($_SESSION['account']) ? $_SESSION['account'] : null;
    }
}