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
        $Cap = Capteur::getCapteurFromId((int)$id);
        $idLot = $Cap->getIdLot();
        $lot = Lot::getLotFromID($idLot);

        Capteur::UpdateValid($id);
        $lot->updateValid();

        $idEssai = $Cap->getidEssai();
        $essai = Essai::getEssaiFromId($idEssai);
        $essai->updateValid();



    }
    header("Location: ../lot.php?id=$idLot");
exit();