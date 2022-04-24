<?php

require_once ("../autoload.php");

for ($i = 0; $i<10000; $i++) {
    $temps = $i*10;
    $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Valeur (time, value, valid, idCap) VALUES ( $temps, 0, 1, 183)
SQL);
    $result->execute();
}