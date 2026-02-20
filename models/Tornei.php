<?php

/* 
*  ClassName: Tornei
*  Generated: 2026-02-18 10:22:37
*  Author: Andrea Carminati (default)
*  Table: tornei
*  Database: burraco
*/

define("TORNEI_TABLE","tornei");

class Tornei extends DBObject
{
	protected $idtorneo;
	protected $nometorneo;
	protected $statotorneo;

	// Class Constructor
	public function __construct() {
		parent::__construct(TORNEI_TABLE);
        $this->primaryKey="idtorneo";
		return $this;
	}
	//Getter methods
	function getidtorneo(){
		return $this->idtorneo;
	}

	function getnometorneo(){
		return $this->nometorneo;
	}

	function getstatotorneo(){
		return $this->statotorneo;
	}


	//Setter methods
	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setnometorneo($value){
		$this->nometorneo=$value;
	}

	function setstatotorneo($value){
		$this->statotorneo=$value;
	}

}