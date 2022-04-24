<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('../autoload.php');

if (isset($_GET["idessai"]) && isset($_GET["position"])) {

    $idessai = $_GET["idessai"];
    $position = $_GET["position"];

    $ret = Capteur::createCapteurToDB((int)$idessai, (int)$position);
    echo "<p>$ret</p>";
}