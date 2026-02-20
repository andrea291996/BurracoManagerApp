<?php

/* 
*  ClassName: Accountamministratori
*  Generated: 2026-02-18 08:14:27
*  Author: Andrea Carminati (default)
*  Table: accountamministratori
*  Database: burraco
*/

define("ACCOUNTAMMINISTRATORI_TABLE","accountamministratori");

class Accountamministratori extends DBObject
{
	protected $idamministratore;
	protected $email;
	protected $password;

	// Class Constructor
	public function __construct() {
		parent::__construct(ACCOUNTAMMINISTRATORI_TABLE);
        $this->primaryKey="idamministratore";
		return $this;
	}
	//Getter methods
	function getidamministratore(){
		return $this->idamministratore;
	}

	function getemail(){
		return $this->email;
	}

	function getpassword(){
		return $this->password;
	}


	//Setter methods
	function setidamministratore($value){
		$this->idamministratore=$value;
	}

	function setemail($value){
		$this->email=$value;
	}

	function setpassword($value){
		$this->password=$value;
	}

	//Helper
	function isAmministratore() { return true; }
	function isGiocatore() { return false; }
	function isCircolo() { return false; }
	function isAnonimo() { return false; }
	function dimmiTipolgiaUtente(){
		return "amministratore";
	}

}