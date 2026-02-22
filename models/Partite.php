<?php

/* 
*  ClassName: Partite
*  Generated: 2026-02-22 15:57:33
*  Author: Andrea Carminati (default)
*  Table: partite
*  Database: burraco
*/

define("PARTITE_TABLE","partite");

class Partite extends DBObject
{
	protected $idpartita;
	protected $squadra1;
	protected $squadra2;
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

	function getsquadra1(){
		return $this->squadra1;
	}

	function getsquadra2(){
		return $this->squadra2;
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

	function setsquadra1($value){
		$this->squadra1=$value;
	}

	function setsquadra2($value){
		$this->squadra2=$value;
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