<?php


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('../autoload.php');

header("Refresh:2");

if(isset($_GET["idCap"])) {
    $idCap = $_GET["idCap"];
    $seuil = 0;



    $cap = Capteur::getCapteurFromId($idCap);
    $hs = Capteur::getHS($idCap);
    $idLot = $cap->getIdLot();

if($hs != 1) {
    $seuil = Lot::GetSeuilFromIdLot($idLot);
    echo "<p>$seuil</p>";
}else{
    echo "<p>-1</p>";
}



}

