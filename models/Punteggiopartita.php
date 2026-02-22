<?php

/* 
*  ClassName: Punteggiopartita
*  Generated: 2026-02-22 16:08:14
*  Author: Andrea Carminati (default)
*  Table: punteggiopartita
*  Database: burraco
*/

define("PUNTEGGIOPARTITA_TABLE","punteggiopartita");

class Punteggiopartita extends DBObject
{
	protected $idpunteggiopartita;
	protected $idpartita;
	protected $idsquadra;
	protected $punteggio;

	// Class Constructor
	public function __construct() {
		parent::__construct(PUNTEGGIOPARTITA_TABLE);
        $this->primaryKey="idpunteggiopartita";
		return $this;
	}
	//Getter methods
	function getidpunteggiopartita(){
		return $this->idpunteggiopartita;
	}

	function getidpartita(){
		return $this->idpartita;
	}

	function getidsquadra(){
		return $this->idsquadra;
	}

	function getpunteggio(){
		return $this->punteggio;
	}


	//Setter methods
	function setidpunteggiopartita($value){
		$this->idpunteggiopartita=$value;
	}

	function setidpartita($value){
		$this->idpartita=$value;
	}

	function setidsquadra($value){
		$this->idsquadra=$value;
	}

	function setpunteggio($value){
		$this->punteggio=$value;
	}

}