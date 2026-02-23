<?php

/* 
*  ClassName: Partite
*  Generated: 2026-02-23 10:05:50
*  Author: Andrea Carminati (default)
*  Table: partite
*  Database: burraco
*/

define("PARTITE_TABLE","partite");

class Partite extends DBObject
{
	protected $idpartita;
	protected $idsquadra1;
	protected $idsquadra2;
	protected $idcircolo;
	protected $idtorneo;
	protected $giornata;
	protected $turno;
	protected $distanzapercorsainquinandodagiocatorimetri;

	// Class Constructor
	public function __construct() {
		parent::__construct(PARTITE_TABLE);
        $this->primaryKey="idpartita";
		return $this;
	}
	//Getter methods
	function getidpartita(){
		return $this->idpartita;
	}

	function getidsquadra1(){
		return $this->idsquadra1;
	}

	function getidsquadra2(){
		return $this->idsquadra2;
	}

	function getidcircolo(){
		return $this->idcircolo;
	}

	function getidtorneo(){
		return $this->idtorneo;
	}

	function getgiornata(){
		return $this->giornata;
	}

	function getturno(){
		return $this->turno;
	}

	function getdistanzapercorsainquinandodagiocatorimetri(){
		return $this->distanzapercorsainquinandodagiocatorimetri;
	}


	//Setter methods
	function setidpartita($value){
		$this->idpartita=$value;
	}

	function setidsquadra1($value){
		$this->idsquadra1=$value;
	}

	function setidsquadra2($value){
		$this->idsquadra2=$value;
	}

	function setidcircolo($value){
		$this->idcircolo=$value;
	}

	function setidtorneo($value){
		$this->idtorneo=$value;
	}

	function setgiornata($value){
		$this->giornata=$value;
	}

	function setturno($value){
		$this->turno=$value;
	}

	function setdistanzapercorsainquinandodagiocatorimetri($value){
		$this->distanzapercorsainquinandodagiocatorimetri=$value;
	}

}