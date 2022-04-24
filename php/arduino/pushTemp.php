<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('../autoload.php');

if (isset($_POST["idessai"])  && isset($_POST["time"]) && isset($_POST["value"])) {

    $idessai = $_POST["idessai"];
    $time = $_POST["time"];
    $value = $_POST["value"];

    $ret = Temperature::createTempToDB((int)$idessai, (int)$time, (float)$value);
    echo "<p>$ret</p>";
}