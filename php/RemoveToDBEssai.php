<?php

require_once('autoload.php');
require_once('src/Essai.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


$id = $_GET['id'];
$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$user = $authentication->getUserFromSession();




if ($user->getRole() == 'Administrateur') {

    $lot=Lot::GetidLotEssai($id);
    foreach ($lot as $idLot){
        $nbEssai = Lot::GetnbEssaiLot($idLot);
        if ($nbEssai == 1){
            Lot::removeToDBLot($idLot);
        }
    }


    Lot::RemoveFromPosseder($id);
    $essai = Essai::getEssaiFromID($id);
    $essai->RemovetoDB();


}

header('Location: ../index.php');
exit();
