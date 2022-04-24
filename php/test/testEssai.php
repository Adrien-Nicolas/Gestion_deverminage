<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('../autoload.php');



/*
$ess = Essai::getEssaiFromId(2);
echo 'id : '.$ess->getId()."\n";
echo 'Date string : '.$ess->getDateString()."\n";
echo 'Date : '.$ess->getDate()."\n";
echo 'id Op : '.$ess->getIdOp()."\n";
echo 'F Cap : '.$ess->getFirstCap(1)."\n";
echo 'L Cap : '.$ess->getLastCap(1)."\n";
echo 'F Cap : '.$ess->getFirstCap(2)."\n";
echo 'L Cap : '.$ess->getLastCap(2)."\n";
echo 'L Cap : '.$ess->getLastCapDate()."\n";
echo 'L Cap : '.$ess-> getLastCapDateString()."\n";
echo 'L Cap : '.$ess-> getDateString(true)."\n";
echo 'Nb Valid Cap : '.$ess->getNbValidLot()."\n";
echo 'Nb Not Valid Cap : '.$ess->getNbNotValidLot()."\n";
echo 'Nb Cap : '.$ess->getNbCap()."\n";
echo 'Nb Cap Not Valid: '.$ess->getNbCapNotValid()."\n";
*/

$id = 7;

$ess= Essai::getEssaiFromId($id);

$idProd = $ess->getidProduit();



$nameProduct = Essai::getProduitidEssai($idProd);
var_dump($nameProduct);
