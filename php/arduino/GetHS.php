<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('../autoload.php');

if (isset($_GET["idCap"])) {

    $idCap = $_GET["idCap"];

    $hs = Capteur::getHS($idCap);
    echo "<p>$hs</p>";
}