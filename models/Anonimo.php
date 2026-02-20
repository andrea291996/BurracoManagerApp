<?php

class Anonimo{
    //Helper
	function isAmministratore() { return false; }
	function isGiocatore() { return false; }
	function isCircolo() { return false; }
	function isAnonimo() { return true; }
	function dimmiTipolgiaUtente(){
		return "anonimo";
	}
}