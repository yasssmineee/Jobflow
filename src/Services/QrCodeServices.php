<?php

namespace App\Services;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;




class QrCodeServices 
{
protected $builder;
public function __construct(BuilderInterface $builder){
    $this->builder=$builder;
}
public function qrcode($query){
    $baseURL = 'https://www.google.com/search?client';
    
    // Encodage de la requête
    $encodedQuery = urlencode($query);

    $objDateTime= new \DateTime('NOW');
    $dateString= $objDateTime->format('d-m-y H:i:s');

    $result = $this->builder
        ->data($baseURL . '&q=' . $encodedQuery) // Ajoute la requête encodée comme paramètre de recherche
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High) // Utilisation de la casse correcte pour la constante
        ->margin(10)
        ->size(250)
        ->labelText($dateString)
        ->build();

    $namePng = uniqid('', true) . '.png';
    $result->saveToFile(\dirname(__DIR__, 2) . '/public/assets/qrcode/' . $namePng);
    return $result->getDataUri();
}

}
