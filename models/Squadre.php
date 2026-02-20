<?php

/* 
*  ClassName: Squadre
*  Generated: 2026-02-18 10:22:31
*  Author: Andrea Carminati (default)
*  Table: squadre
*  Database: burraco
*/

define("SQUADRE_TABLE","squadre");

class Squadre extends DBObject
{
	protected $idtorneo;
	protected $idsquadra;
	protected $idcompagnomittente;
	protected $idcompagnodestinatario;

	// Class Constructor
	public function __construct() {
		parent::__construct(SQUADRE_TABLE);
        $this->primaryKey="idsquadra";
		return $this;
	}
	//Getter methods
	function getidtorneo(){
		return $this->idtorneo;
	}

	function getidsquadra(){
		return $this->idsquadra;
	}

	function getidcompagnomittente(){
		return $this->idcompagnomittente;
	}

	function getidcompagnodestinatario(){
		return $this->idcompagnodestinatario;
	}


	//Setter methods
	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setidsquadra($value){
		$this->idsquadra=$value;
	}

	function setidcompagnomittente($value){
		$this->idcompagnomittente=$value;
	}

	function setidcompagnodestinatario($value){
		$this->idcompagnodestinatario=$value;
	}

}