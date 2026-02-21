<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/*
Risultato di dammiDistanzeSquadreCircoli($squadre, $circoli)
array [
    [idsquadra] => [
            [idcircolo] => distanzainquinante
    ]
    [idquadra]  => [ 
            [idcircolo] => distanzaindquinante
    ]
    ...
    ...
]
*/

class DistanzeRepository{

    protected $squadraRepository;
    protected $utentiRepository;

    public function __construct()
    {
        $this->squadraRepository = new SquadraRepository();
        $this->utentiRepository = new UtentiRepository();
    }

    public function dammiDistanzeSquadreCircoli($squadre, $circoli){
        $matriceDistanze = [];
        foreach($squadre as $squadra){
            $idSquadra = $squadra->getidsquadra();
            foreach($circoli as $circolo){
                $idCircolo = $circolo->getidcircolo();
                $matriceDistanze[$idSquadra][$idCircolo] = $this->dammiDistanzaSquadraCircolo($squadra, $circolo);
            }
        }
        return $matriceDistanze;
    }

    public function dammiDistanzaSquadraCircolo($squadra, $circolo){
        $idCompagno1 = $squadra->getidcompagnodestinatario();
        $compagno1 = new Accountgiocatori();
        $compagno1->select(['idgiocatore'=>$idCompagno1]);
        $idCompagno2 = $squadra->getidcompagnomittente();
        $compagno2 = new Accountgiocatori();
        $compagno2->select(['idgiocatore'=>$idCompagno2]);
        $indirizzoCompagno1 = $this->dammiIndirizzoGiocatore($compagno1);
        $indirizzoCompagno2 = $this->dammiIndirizzoGiocatore($compagno2);
        $indirizzoCircolo = $this->dammiIndirizzoCircolo($circolo);
        $distanza1 = $this->calcolaDistanza($indirizzoCompagno1, $indirizzoCircolo);
        $distanzaDispostoAfareSenzaInquinare1 = $this->dammiDistanzaDispostoAFareSenzaInquinare($compagno1) / 1000;
        $distanza2 = $this->calcolaDistanza($indirizzoCompagno2, $indirizzoCircolo);
        $distanzaDispostoAfareSenzaInquinare2 = $this->dammiDistanzaDispostoAFareSenzaInquinare($compagno2) / 1000;
        if($distanza1 < $distanzaDispostoAfareSenzaInquinare1){
            $distanza1 = 0;
        }
        if($distanza2 < $distanzaDispostoAfareSenzaInquinare2){
            $distanza2 = 0;
        }
        $distanzaTotaleDaPercorrereInquinando = $distanza1 + $distanza2;
        return $distanzaTotaleDaPercorrereInquinando;
    }

    public function dammiIndirizzoGiocatore($giocatore){
        $idGiocatore = $giocatore->getidgiocatore();
        $profilo = new Profiligiocatori();
        $profilo->select(['idaccountgiocatore' => $idGiocatore]);
        return [
            'latitudine' => $profilo->getlatitudine(),
            'longitudine'=> $profilo->getlongitudine()
        ];
    }

    public function dammiDistanzaDispostoAFareSenzaInquinare($giocatore){
        $idGiocatore = $giocatore->getidgiocatore();
        $profilo = new Profiligiocatori();
        $profilo->select(['idaccountgiocatore' => $idGiocatore]);
        return $profilo->getdistanzanoninquinanteinmetri();
    }

    public function dammiIndirizzoCircolo($circolo){
        $idCircolo = $circolo->getidcircolo();    
        $profilo = new Profilicircoli();
        $profilo->select(['idaccountcircolo' => $idCircolo]);
        return [
            'latitudine' => $profilo->getlatitudine(),
            'longitudine'=> $profilo->getlongitudine()
        ];
    }

    public function calcolaDistanza($indirizzoGiocatore, $indirizzoCircolo){
        $lat1 = $indirizzoGiocatore['latitudine'];
        $lon1 = $indirizzoGiocatore['longitudine'];
        $lat2 = $indirizzoCircolo['latitudine'];
        $lon2 = $indirizzoCircolo['longitudine'];
        $raggioTerra = 6371; // In KM
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $raggioTerra * $c;
    }

    public static function getCoordinate($address) {
    $address = urlencode($address);
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}";
    $opts = ['http' => ['header' => "User-Agent: MioProgettoBurraco/1.0\r\n"]];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon']
        ];
    }
    return null;
}

}