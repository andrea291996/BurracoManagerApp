<?php

/* 
*  ClassName: Totali
*  Generated: 2026-02-24 10:45:38
*  Author: Andrea Carminati (default)
*  Table: totali
*  Database: burraco
*/

define("TOTALI_TABLE","totali");

class Totali extends DBObject
{
	protected $idtotale;
	protected $idtorneo;
	protected $idsquadra;
	protected $totale;

	// Class Constructor
	public function __construct() {
		parent::__construct(TOTALI_TABLE);
        $this->primaryKey="idtotale";
		return $this;
	}
	//Getter methods
	function getidtotale(){
		return $this->idtotale;
	}

	function getidtorneo(){
		return $this->idtorneo;
	}

	function getidsquadra(){
		return $this->idsquadra;
	}

	function gettotale(){
		return $this->totale;
	}


	//Setter methods
	function setidtotale($value){
		$this->idtotale=$value;
	}

	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setidsquadra($value){
		$this->idsquadra=$value;
	}

	function settotale($value){
		$this->totale=$value;
	}

}