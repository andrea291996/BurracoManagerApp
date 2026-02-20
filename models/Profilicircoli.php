<?php

/* 
*  ClassName: Profilicircoli
*  Generated: 2026-02-18 09:22:22
*  Author: Andrea Carminati (default)
*  Table: profilicircoli
*  Database: burraco
*/

define("PROFILICIRCOLI_TABLE","profilicircoli");

class Profilicircoli extends DBObject
{
	protected $idprofilocircolo;
	protected $idaccountcircolo;
	protected $indirizzo;

	// Class Constructor
	public function __construct() {
		parent::__construct(PROFILICIRCOLI_TABLE);
        $this->primaryKey="idprofilocircolo";
		return $this;
	}
	//Getter methods
	function getidprofilocircolo(){
		return $this->idprofilocircolo;
	}

	function getidaccountcircolo(){
		return $this->idaccountcircolo;
	}

	function getindirizzo(){
		return $this->indirizzo;
	}


	//Setter methods
	function setidprofilocircolo($value){
		$this->idprofilocircolo=$value;
	}

	function setidaccountcircolo($value){
		$this->idaccountcircolo=$value;
	}

	function setindirizzo($value){
		$this->indirizzo=$value;
	}

}