<?php

/* 
*  ClassName: Giocatoretorneo
*  Generated: 2026-02-18 10:22:11
*  Author: Andrea Carminati (default)
*  Table: giocatoretorneo
*  Database: burraco
*/

define("GIOCATORETORNEO_TABLE","giocatoretorneo");

class Giocatoretorneo extends DBObject
{
	protected $idgiocatoretorneo;
	protected $idtorneo;
	protected $idgiocatore;

	// Class Constructor
	public function __construct() {
		parent::__construct(GIOCATORETORNEO_TABLE);
        $this->primaryKey="idgiocatoretorneo";
		return $this;
	}
	//Getter methods
	function getidgiocatoretorneo(){
		return $this->idgiocatoretorneo;
	}

	function getidtorneo(){
		return $this->idtorneo;
	}

	function getidgiocatore(){
		return $this->idgiocatore;
	}


	//Setter methods
	function setidgiocatoretorneo($value){
		$this->idgiocatoretorneo=$value;
	}

	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setidgiocatore($value){
		$this->idgiocatore=$value;
	}

}