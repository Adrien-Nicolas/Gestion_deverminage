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
    $id = $_POST['id'];
    $value = $_POST['Seuil'];


    if (isset($_POST['Seuil'])) {

        Lot::UpdateSeuil(1, $value);

    }
}


header("Location: ../product.php");
exit();
