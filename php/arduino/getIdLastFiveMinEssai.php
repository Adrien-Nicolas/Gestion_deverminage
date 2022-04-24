<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once ('../Essai.php');
require_once ('../MyPDO.php');

$ret = Essai::getIdLastFiveMinEssai();
echo "<p>$ret</p>";