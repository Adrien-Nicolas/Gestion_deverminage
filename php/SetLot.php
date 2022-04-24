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
    $desc = $_POST['descLot'];


    if (isset($_POST['descLot'])) {

        Lot::updateDesc($id, $desc);

    }
}
header("Location: ../lot.php?id=$id");
exit();