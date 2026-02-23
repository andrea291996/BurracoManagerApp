<?php

/* 
*  ClassName: Punteggipartita
*  Generated: 2026-02-23 11:02:26
*  Author: Andrea Carminati (default)
*  Table: punteggipartita
*  Database: burraco
*/

define("PUNTEGGIPARTITA_TABLE","punteggipartita");

class Punteggipartita extends DBObject
{
	protected $idpartita;
	protected $idsquadra;
	protected $punteggio;
	protected $idpunteggipartita;

	// Class Constructor
	public function __construct() {
		parent::__construct(PUNTEGGIPARTITA_TABLE);
        $this->primaryKey="idpunteggipartita";
		return $this;
	}
	//Getter methods
	function getidpartita(){
		return $this->idpartita;
	}

	function getidsquadra(){
		return $this->idsquadra;
	}

	function getpunteggio(){
		return $this->punteggio;
	}

	function getidpunteggipartita(){
		return $this->idpunteggipartita;
	}


	//Setter methods
	function setidpartita($value){
		$this->idpartita=$value;
	}

	function setidsquadra($value){
		$this->idsquadra=$value;
	}

	function setpunteggio($value){
		$this->punteggio=$value;
	}

	function setidpunteggipartita($value){
		$this->idpunteggipartita=$value;
	}

}