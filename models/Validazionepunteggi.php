<?php

/* 
*  ClassName: Validazionepunteggi
*  Generated: 2026-02-22 15:57:39
*  Author: Andrea Carminati (default)
*  Table: validazionepunteggi
*  Database: burraco
*/

define("VALIDAZIONEPUNTEGGI_TABLE","validazionepunteggi");

class Validazionepunteggi extends DBObject
{
	protected $idvalidazionepunteggio;
	protected $idgiocatore;
	protected $idpartita;
	protected $idsquadra1;
	protected $idsquadra2;
	protected $punteggiosquadra1;
	protected $punteggiosquadra2;

	// Class Constructor
	public function __construct() {
		parent::__construct(VALIDAZIONEPUNTEGGI_TABLE);
        $this->primaryKey="idvalidazionepunteggio";
		return $this;
	}
	//Getter methods
	function getidvalidazionepunteggio(){
		return $this->idvalidazionepunteggio;
	}

	function getidgiocatore(){
		return $this->idgiocatore;
	}

	function getidpartita(){
		return $this->idpartita;
	}

	function getidsquadra1(){
		return $this->idsquadra1;
	}

	function getidsquadra2(){
		return $this->idsquadra2;
	}

	function getpunteggiosquadra1(){
		return $this->punteggiosquadra1;
	}

	function getpunteggiosquadra2(){
		return $this->punteggiosquadra2;
	}


	//Setter methods
	function setidvalidazionepunteggio($value){
		$this->idvalidazionepunteggio=$value;
	}

	function setidgiocatore($value){
		$this->idgiocatore=$value;
	}

	function setidpartita($value){
		$this->idpartita=$value;
	}

	function setidsquadra1($value){
		$this->idsquadra1=$value;
	}

	function setidsquadra2($value){
		$this->idsquadra2=$value;
	}

	function setpunteggiosquadra1($value){
		$this->punteggiosquadra1=$value;
	}

	function setpunteggiosquadra2($value){
		$this->punteggiosquadra2=$value;
	}

}