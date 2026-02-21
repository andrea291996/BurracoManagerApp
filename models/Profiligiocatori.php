<?php

/* 
*  ClassName: Profiligiocatori
*  Generated: 2026-02-21 22:20:55
*  Author: Andrea Carminati (default)
*  Table: profiligiocatori
*  Database: burraco
*/

define("PROFILIGIOCATORI_TABLE","profiligiocatori");

class Profiligiocatori extends DBObject
{
	protected $idprofilogiocatore;
	protected $idaccountgiocatore;
	protected $indirizzo;
	protected $distanzanoninquinanteinmetri;
	protected $latitudine;
	protected $longitudine;

	// Class Constructor
	public function __construct() {
		parent::__construct(PROFILIGIOCATORI_TABLE);
        $this->primaryKey="idprofilogiocatore";
		return $this;
	}
	//Getter methods
	function getidprofilogiocatore(){
		return $this->idprofilogiocatore;
	}

	function getidaccountgiocatore(){
		return $this->idaccountgiocatore;
	}

	function getindirizzo(){
		return $this->indirizzo;
	}

	function getdistanzanoninquinanteinmetri(){
		return $this->distanzanoninquinanteinmetri;
	}

	function getlatitudine(){
		return $this->latitudine;
	}

	function getlongitudine(){
		return $this->longitudine;
	}


	//Setter methods
	function setidprofilogiocatore($value){
		$this->idprofilogiocatore=$value;
	}

	function setidaccountgiocatore($value){
		$this->idaccountgiocatore=$value;
	}

	function setindirizzo($value){
		$this->indirizzo=$value;
	}

	function setdistanzanoninquinanteinmetri($value){
		$this->distanzanoninquinanteinmetri=$value;
	}

	function setlatitudine($value){
		$this->latitudine=$value;
	}

	function setlongitudine($value){
		$this->longitudine=$value;
	}

}