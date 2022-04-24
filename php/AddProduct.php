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
    $desc = $_POST['productName2'];
    $seuil = $_POST['SeuilName'];

    if (isset($_POST['productName2']) && isset($_POST['SeuilName'])){

        Lot::createProduct($desc, $seuil);

    }
}


header("Location: ../product.php");
exit();

