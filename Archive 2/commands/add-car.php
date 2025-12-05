<?php
use com\igkdev\projects\CarRental\Actions\Api\CarsAction;
use IGK\System\Http\Request;
$src = <<<'JSON'
{
    "lastName": "BONDJE",
    "firstName": "Charles",
    "gsm": "+32486684697",
    "email": "bondje.doue@gmail.com",
    "rue_numero": "",
    "code_postal_commune_ville": "",
    "telephone": "",
    "fax": "",
    "telephone_professionnel": "",
    "fax_professionnel": "",
    "marque": "BMW",
    "modele": "296",
    "categorie": "Occasion",
    "carburant": "Essence",
    "boite": "Manuelle",
    "porte": "4",
    "nb_vitesse": "5",
    "valves": "16",
    "horsepower": "55",
    "europe_n": "E6",
    "title": "1.2 16V 75 E6",
    "couleur": "Bleu",
    "cylindre": "1.2",
    "mois": "01-1999",
    "kilometrage": "51980",
    "garantie": "3",
    "price": "22131",
    "description_complementaire": "description is ok",
    "options": [
        "jantes_alus",
        "pre_tel",
        "toit_ouvrant_panoramique",
        "vitres_electriques"
    ],
    "taille_jante": "",
    "nb_airbag": "",
    "input_files": ""
}
JSON;
$ctrl = CarRentalController::ctrl();
$ctrl->register_autoload();
$g = Request::getInstance(); 
$g->setJsonData($src); 
$ac = new CarsAction;
$ac->proposal_post($g);