<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('autoload.php');


$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$user = $authentication->getUserFromSession();



    if ($user->getRole() == 'Administrateur') {

        $id = $_GET['id'];
        $cap = Capteur::getCapteurFromId((int)$id);
        $idLot = $cap->getIdLot();
        $lot = Lot::getLotFromID($idLot);
        Capteur::UpdateNotValid($id);
        $lot->updateValid();
        $idEssai = $cap->getidEssai();
        $essai = Essai::getEssaiFromId($idEssai);
        $essai->updateValid();


        header("Location: ../lot.php?id=$idLot");
        exit();
    }else{
        exit();
    }