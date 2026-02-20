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
    //SQUADRE
    [
        'pattern' => '/miasquadra',
        'callable' => 'SquadraController:mostraMiaSquadra'
    ],
    [
        'pattern' => '/squadre',
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
    //AZIONI
    [
        'pattern' => '/iscrivi',
        'callable' => 'TorneoController:iscrivi'
    ],
    [
        'pattern' => '/disiscrivi',
        'callable' => 'TorneoController:disiscrivi'
    ]
    
];

?>