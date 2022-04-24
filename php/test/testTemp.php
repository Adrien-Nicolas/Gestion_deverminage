<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);



require_once ('../autoload.php');

$datetime = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('Europe/Paris'));
$date = $datetime->format('Y-m-d H:i:s');

$id = 2;
$temperature = Temperature::getTempFromID($id);

$essai = Temperature::createTempToDB(1, 12506, 25);
var_dump($essai);
