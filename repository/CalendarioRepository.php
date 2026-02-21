<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CalendarioRepository{

    protected $squadraRepository;
    protected $torneoRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->torneoRepository = new TorneoRepository();
    }

}