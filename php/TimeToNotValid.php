<?php
header('Location: https://projets3.go.yj.fr/lot.php');

require_once('../lot.php');
$id = $_GET['id'];

$lot = Lot::getLotFromID($id);
$lot->getTimeNotValid($id);

