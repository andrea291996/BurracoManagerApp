<?php

/* 
*  ClassName: Profilicircoli
*  Generated: 2026-02-21 22:20:48
*  Author: Andrea Carminati (default)
*  Table: profilicircoli
*  Database: burraco
*/

define("PROFILICIRCOLI_TABLE","profilicircoli");

class Profilicircoli extends DBObject
{
	protected $idprofilocircolo;
	protected $idaccountcircolo;
	protected $indirizzo;
	protected $latitudine;
	protected $longitudine;

	// Class Constructor
	public function __construct() {
		parent::__construct(PROFILICIRCOLI_TABLE);
        $this->primaryKey="idprofilocircolo";
		return $this;
	}
	//Getter methods
	function getidprofilocircolo(){
		return $this->idprofilocircolo;
	}

	function getidaccountcircolo(){
		return $this->idaccountcircolo;
	}

	function getindirizzo(){
		return $this->indirizzo;
	}

	function getlatitudine(){
		return $this->latitudine;
	}

	function getlongitudine(){
		return $this->longitudine;
	}


	//Setter methods
	function setidprofilocircolo($value){
		$this->idprofilocircolo=$value;
	}

	function setidaccountcircolo($value){
		$this->idaccountcircolo=$value;
	}

	function setindirizzo($value){
		$this->indirizzo=$value;
	}

	function setlatitudine($value){
		$this->latitudine=$value;
	}

	function setlongitudine($value){
		$this->longitudine=$value;
	}

}