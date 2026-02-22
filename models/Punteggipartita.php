<?php

/* 
*  ClassName: Punteggipartita
*  Generated: 2026-02-22 15:57:36
*  Author: Andrea Carminati (default)
*  Table: punteggipartita
*  Database: burraco
*/

define("PUNTEGGIPARTITA_TABLE","punteggipartita");

class Punteggipartita extends DBObject
{
	protected $idpunteggiopartita;
	protected $idpartita;
	protected $idsquadra;
	protected $punteggio;

	// Class Constructor
	public function __construct() {
		parent::__construct(PUNTEGGIPARTITA_TABLE);
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