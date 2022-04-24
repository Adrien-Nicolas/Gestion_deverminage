<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('../autoload.php');


if(isset($_POST["value"]) && isset($_POST["time"]) && isset($_POST["valid"]) && isset($_POST["idCap"])) {
    $value = $_POST["value"];
    $time = $_POST["time"];
    $valid = $_POST["valid"];
    $idCap = $_POST["idCap"];


    Valeur::uploadValeur((int)$time, (int)$value, (int)$valid, (int) $idCap);


    if($valid == 0){

        $cap = Capteur::getCapteurFromId($idCap);
        Capteur::UpdateNotValid($idCap);
        $idEssai = $cap->getidEssai();
        echo $idEssai;
        $essai = Essai::getEssaiFromId($idEssai);
        Capteur::UpdateNotValid($idCap);
        $lots = $essai->getLots();

        foreach ($lots as $retlot){
            $lot = Lot::getLotFromID($retlot->getId());
            $lot->updateValid();
        }
        $essai->updateValid();



    }


}
