<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('../ConfigGraph.php');

$conf = ConfigGraph::getConfigFromId(1);



echo 'id : '.$conf->getId()."\n";
echo 'getseuilBas : '.$conf->getseuilBas()."\n";
echo 'getseuilHaut : '.$conf->getseuilHaut()."\n";
echo 'getnomBleu : '.$conf->getnomBleu()."\n";
echo 'getnomRouge : '.$conf->getnomRouge()."\n";
echo 'getnomVert : '.$conf->getnomVert()."\n";
echo 'getnomOrange : '.$conf->getnomOrange()."\n";

