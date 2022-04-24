<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('../Temperature.php');
require_once ('../MyPDO.php');

if (isset($_GET["idessai"])  && isset($_GET["time"]) && isset($_GET["value"])) {

    $idessai = $_GET["idessai"];
    $time = $_GET["time"];
    $temp = $_GET["value"];


    $ret = Temperature::createTempToDB((int)$idessai, (int)$time, (float)$temp);
    echo "<p>$ret</p>";
}