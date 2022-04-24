<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('autoload.php');
/*
$time = time()*1000;
*/

$sql = "SELECT time FROM Essai WHERE id = 1";
$result = MyPDO::getInstance()->prepare($sql);
$result->execute();
$timestamp = $result->fetch();
echo $timestamp["time"];
$temps = 0;
$nbex = 0;

for ($i = 0 ; $i<6; $i++) {

    $temps += 200*5*60*50; //50 minutes

    for ($j =0; $j<5; $j++) {
        echo 'je suis la !!';
        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 0, 1, 183)
SQL);
        $result->execute();

        for ($k =0; $k<1500; $k++) {
            $nbex++;
            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 800, 1, 183)
SQL);
            $result->execute();
            $temps+=200;
        }
        $nbex++;
        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 0, 1, 183)
SQL);
        $result->execute();
        $temps+=200;
        $temps += 300000; //5 minutes
    }
    $temps += 480000;//45 minutes
    for ($j =0; $j<5; $j++) {

        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 0, 1, 183)
SQL);
        $result->execute();
        for ($k =0; $k<1500; $k++) {
            $nbex++;
            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 800, 1, 183)
SQL);
            $result->execute();
            $temps+=200;
        }
        $nbex++;
        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ($temps, 0, 1, 183)
SQL);
        $result->execute();
        $temps+=200;
        $temps += 300000; //5 minutes
    }
}
echo "nombre d'execution : ".$nbex;

echo "temps : ".$temps;


