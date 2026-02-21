<?php

$routes = [];

//elenco delle rotte chiamate con GET
$routes['GET'] = [
    [
        'pattern' => '/',
        'callable' => 'HomeController:home'
    ],
    [
        'pattern' => '/login',
        'callable' => 'UserAccountController:login'
    ],
    [
        'pattern' => '/logout',
        'callable' => 'UserAccountController:logout'
    ],
    [
        'pattern' => '/registrazione',
        'callable' => 'UserRegistrationController:registration'
    ],
    //TORNEI
    [
        'pattern' => '/tornei',
        'callable' => 'TorneoController:mostraTuttiTornei'
    ],
    [
        'pattern' => '/infotorneo',
        'callable' => 'TorneoController:infoTorneo'
    ],
    [
        'pattern' => '/mieitornei',
        'callable' => 'TorneoController:mostraMieiTornei'
    ],
    [
        'pattern' => '/creanuovotorneo',
        'callable' => 'TorneoController:mostraCreaNuovoTorneo'
    ],
    //SQUADRE
    [
        'pattern' => '/miasquadra',
        'callable' => 'SquadraController:mostraMiaSquadra'
    ],
    [
        'pattern' => '/tuttesquadre',
        'callable' => 'SquadraController:mostraTutteSquadre'
    ],
    //ISCRITTI
    [
        'pattern' => '/utentiiscritti',
        'callable' => 'IscrizioneController:mostraUtentiIscritti'
    ],
    [
        'pattern' => '/circoliiscritti',
        'callable' => 'IscrizioneController:mostraCircoliIscritti'
    ],
    //GIOCATORI 
    [
        'pattern' => '/giocatorisingle',
        'callable' => 'IscrizioneController:mostraGiocatoriSingle'
    ],
    //UTENTI
    [
        'pattern' => '/utenti',
        'callable' => 'UtentiController:mostraTuttiGiocatori'
    ]
];

$routes['POST'] = [
    [
        'pattern' => '/login',
        'callable' => 'UserAccountController:doLogin'
    ],
    [
        'pattern' => '/registrazione',
        'callable' => 'UserRegistrationController:doRegistration'
    ],
    //TORNEO
    [
        'pattern' => '/iscrivi',
        'callable' => 'TorneoController:iscrivi'
    ],
    [
        'pattern' => '/disiscrivi',
        'callable' => 'TorneoController:disiscrivi'
    ],
    [
        'pattern' => '/creanuovotorneo',
        'callable' => 'TorneoController:CreaNuovoTorneo'
    ],
    //RICHIESTA SQUADRA
    [
        'pattern' => '/inviarichiesta',
        'callable' => 'SquadraController:inviaRichiesta'
    ],
    [
        'pattern' => '/annullarichiesta',
        'callable' => 'SquadraController:annullaRichiesta'
    ],
    [
        'pattern' => '/accettarichiesta',
        'callable' => 'SquadraController:accettaRichiesta'
    ],
    [
        'pattern' => '/rifiutarichiesta',
        'callable' => 'SquadraController:rifiutaRichiesta'
    ]
];

?>