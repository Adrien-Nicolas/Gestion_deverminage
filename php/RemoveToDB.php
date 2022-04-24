<?php

require_once('autoload.php');
require_once('src/Lot.php');
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
    $id = $_GET['id'];
    $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET idLot = NULL WHERE idLot = :id
SQL);
    $result->bindValue(":id", $id);
    $result->execute();
    $lot = Lot::getLotFromID($id);
    $lot->RemovetoDB($id);
}

header('Location: ../index.php');
exit();

