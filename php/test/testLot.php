<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('../src/Lot.php');
require_once('../src/MyPDO.php');
/*
$product = Lot::getLotFromID(2);

$idPro = $product->getIdProduit();
$nameProduct = $product->getProduct();



$idprod = Lot::getIdProduitByName("Tube LED");

var_dump($idprod);
*/
/*
for($i =0; $i<sizeof($listeProd); $i++) {
    var_dump($listeProd['name']);

}*/



/*
$seuil = Lot::GetSeuil(1);
var_dump($seuil);
*/


$seuil = Lot::GetSeuilFromIdLot(130);
var_dump($seuil);
