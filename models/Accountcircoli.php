<?php

/* 
*  ClassName: Accountcircoli
*  Generated: 2026-02-18 08:14:18
*  Author: Andrea Carminati (default)
*  Table: accountcircoli
*  Database: burraco
*/

define("ACCOUNTCIRCOLI_TABLE","accountcircoli");

class Accountcircoli extends DBObject
{
	protected $idcircolo;
	protected $nome;
	protected $email;
	protected $password;

	// Class Constructor
	public function __construct() {
		parent::__construct(ACCOUNTCIRCOLI_TABLE);
        $this->primaryKey="idcircolo";
		return $this;
	}
	//Getter methods
	function getidcircolo(){
		return $this->idcircolo;
	}

	function getnome(){
		return $this->nome;
	}

	function getemail(){
		return $this->email;
	}

	function getpassword(){
		return $this->password;
	}


	//Setter methods
	function setidcircolo($value){
		$this->idcircolo=$value;
	}

	function setnome($value){
		$this->nome=$value;
	}

	function setemail($value){
		$this->email=$value;
	}

	function setpassword($value){
		$this->password=$value;
	}

	//Helper
	function isAmministratore() { return false; }
	function isGiocatore() { return false; }
	function isCircolo() { return true; }
	function isAnonimo() { return false; }
	function dimmiTipolgiaUtente(){
		return "circolo";
	}
}