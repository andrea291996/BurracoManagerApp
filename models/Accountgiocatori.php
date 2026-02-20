<?php

/* 
*  ClassName: Accountgiocatori
*  Generated: 2026-02-18 08:14:07
*  Author: Andrea Carminati (default)
*  Table: accountgiocatori
*  Database: burraco
*/

define("ACCOUNTGIOCATORI_TABLE","accountgiocatori");

class Accountgiocatori extends DBObject
{
	protected $idgiocatore;
	protected $nome;
	protected $cognome;
	protected $email;
	protected $password;

	// Class Constructor
	public function __construct() {
		parent::__construct(ACCOUNTGIOCATORI_TABLE);
        $this->primaryKey="idgiocatore";
		return $this;
	}
	//Getter methods
	function getidgiocatore(){
		return $this->idgiocatore;
	}

	function getnome(){
		return $this->nome;
	}

	function getcognome(){
		return $this->cognome;
	}

	function getemail(){
		return $this->email;
	}

	function getpassword(){
		return $this->password;
	}


	//Setter methods
	function setidgiocatore($value){
		$this->idgiocatore=$value;
	}

	function setnome($value){
		$this->nome=$value;
	}

	function setcognome($value){
		$this->cognome=$value;
	}

	function setemail($value){
		$this->email=$value;
	}

	function setpassword($value){
		$this->password=$value;
	}

	//Helper
	function isAmministratore() { return false; }
	function isGiocatore() { return true; }
	function isCircolo() { return false; }
	function isAnonimo() { return false; }
	function dimmiTipolgiaUtente(){
		return "giocatore";
	}
}