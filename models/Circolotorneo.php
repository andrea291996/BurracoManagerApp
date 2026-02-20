<?php

/* 
*  ClassName: Circolotorneo
*  Generated: 2026-02-18 10:22:00
*  Author: Andrea Carminati (default)
*  Table: circolotorneo
*  Database: burraco
*/

define("CIRCOLOTORNEO_TABLE","circolotorneo");

class Circolotorneo extends DBObject
{
	protected $idcircolotorneo;
	protected $idcircolo;
	protected $idtorneo;

	// Class Constructor
	public function __construct() {
		parent::__construct(CIRCOLOTORNEO_TABLE);
        $this->primaryKey="idcircolotorneo";
		return $this;
	}
	//Getter methods
	function getidcircolotorneo(){
		return $this->idcircolotorneo;
	}

	function getidcircolo(){
		return $this->idcircolo;
	}

	function getidtorneo(){
		return $this->idtorneo;
	}


	//Setter methods
	function setidcircolotorneo($value){
		$this->idcircolotorneo=$value;
	}

	function setidcircolo($value){
		$this->idcircolo=$value;
	}

	function setidtorneo($value){
		$this->idtorneo=$value;
	}

}