<?php

/* 
*  ClassName: Profiligiocatori
*  Generated: 2026-02-18 09:22:30
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

}