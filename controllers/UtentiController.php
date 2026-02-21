<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UtentiController extends Controller{

    protected $utentiService;

    public function __construct()
    {
        $this->utentiService = new UtentiService;
    }
    
        
}

