<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('../Utilisateur.php');
require_once('../MyPDO.php');

$test = Utilisateur::getUtilisateurFromId(1);
var_dump($test);

echo $test->getFirstName()."\n";
echo $test->getLastName()."\n";
echo $test->getNickName()."\n";
echo $test->getRole()."\n";
echo $test->getId()."\n";
echo $test->getVkey()."\n";


$users = Utilisateur::getAllUsers();




foreach ($users as $user) {
    echo '<div style="display: flex">';
    echo '<p style="margin: 5px">'.$user->getFirstname().'</p>';
    echo '<p style="margin: 5px">'.$user->getLastname().'</p>';
    echo '<p style="margin: 5px">'.$user->getNickname().'</p>';
    echo '<p style="margin: 5px">'.$user->getRole().'</p>';
    echo '</div>';
}

Utilisateur::createUser('admin', 'istrateur', 'admin@epl.fr', 'Admin', 'Administrateur', 'Epl2021', 'test');

$users = Utilisateur::getAllUsers();

foreach ($users as $user) {
    echo '<div style="display: flex">';
    echo '<p style="margin: 5px">'.$user->getFirstname().'</p>';
    echo '<p style="margin: 5px">'.$user->getLastname().'</p>';
    echo '<p style="margin: 5px">'.$user->getNickname().'</p>';
    echo '<p style="margin: 5px">'.$user->getRole().'</p>';
    echo '</div>';
}