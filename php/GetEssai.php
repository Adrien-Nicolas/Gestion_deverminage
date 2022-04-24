<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('php/autoload.php');

$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$user = $authentication->getUserFromSession();





$idEssai = $_GET['idessai'];


$CapDeb = $_POST['CapDeb$ret'];
$CapFin = $_POST['CapFin$ret'];
$SN = $_POST['SN$ret'];
$numLot = $_POST['numLot$ret'];





$idLot = $cap->getIdLot();
$lot = Lot::getLotFromID($idLot);
Capteur::UpdateNotValid($id);
$lot->updateValid();
$idEssai = $cap->getidEssai();
$essai = Essai::getEssaiFromId($idEssai);
$essai->updateValid();


header("Location: ../essai.php?id=$idEssai");
exit();