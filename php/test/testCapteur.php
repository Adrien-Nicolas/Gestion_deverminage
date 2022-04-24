<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

/*
require_once('/home/cfntjyyq/public_html/test/Capteur.php');
require_once('/home/cfntjyyq/public_html/test/MyPDO.php');

$capteur = Capteur::getCapteurFromId(2);
var_dump($capteur);
$valid = $capteur->getValid();
echo $valid;*/

require_once ('../autoload.php');

/*
$idCap = 419;

$cap = Capteur::getCapteurFromId($idCap);
$idEssai = $cap->getidEssai();
echo $idEssai;
*/

/*

$id = Essai::createEssaiToDB();

for ($i = 1; $i<=60; $i++) {
    Capteur::createCapteurToDB($id, $i);
}
*/


$hs = Capteur::getHS(1596);
echo "<p>$hs</p>";
/*

foreach ($SN as $SNall){
    $SNN = $SNall->getSN();
    var_dump($SNN);
}

/*
$datetime = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('Europe/Paris'));
$date = $datetime->format('Y-m-d H:i:s');

$id = 2;
$capteur = Capteur::getCapteurFromId($id);


Capteur::UpdateCapSet0(5052021, 5);
*/
/*
if($valid == 0){
    $html = <<<HTML
        <iframe title="Graphique de la luminosité par rapport au temps" src="../../graph.php?id=$id" width="100%" height="400px"></iframe>
        <div class="tube-info">
            <div>
                <span> Ce tube a été déconformer à :</span>
                <span>...</span>
            </div>
            <div class="iframerow">
                <a class="cpointer">Cliquez ici pour conformer ce tube</a>
            </div>
        </div>
HTML;
}

else {
    $html = <<<HTML
        <iframe title="Graphique de la luminosité par rapport au temps" src="../../graph.php?id=$id" width="100%" height="400px"></iframe>
        <div class="tube-info">
            <div>
            </div>
            <div class="iframerow">
                <a class="cpointer">Cliquez ici pour déconformer ce tube</a>
            </div>
        </div>
    HTML;
}
echo $html;*/


