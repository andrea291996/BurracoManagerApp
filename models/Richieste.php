<?php

/* 
*  ClassName: Richieste
*  Generated: 2026-02-18 10:22:25
*  Author: Andrea Carminati (default)
*  Table: richieste
*  Database: burraco
*/

define("RICHIESTE_TABLE","richieste");

class Richieste extends DBObject
{
	protected $idtorneo;
	protected $idrichiesta;
	protected $idmittente;
	protected $iddestinatario;
	protected $stato;

	// Class Constructor
	public function __construct() {
		parent::__construct(RICHIESTE_TABLE);
        $this->primaryKey="idrichiesta";
		return $this;
	}
	//Getter methods
	function getidtorneo(){
		return $this->idtorneo;
	}

	function getidrichiesta(){
		return $this->idrichiesta;
	}

	function getidmittente(){
		return $this->idmittente;
	}

	function getiddestinatario(){
		return $this->iddestinatario;
	}

	function getstato(){
		return $this->stato;
	}


	//Setter methods
	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setidrichiesta($value){
		$this->idrichiesta=$value;
	}

	function setidmittente($value){
		$this->idmittente=$value;
	}

	function setiddestinatario($value){
		$this->iddestinatario=$value;
	}

	function setstato($value){
		$this->stato=$value;
	}

}