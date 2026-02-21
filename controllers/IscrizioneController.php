<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IscrizioneController extends Controller{

    protected $torneoService;
    protected $squadraService;
    protected $iscrizioneService;

    public function __construct()
    {
        $this->torneoService = new TorneoService;
        $this->squadraService = new SquadraService;
        $this->iscrizioneService = new IscrizioneService;
    }
}

