<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('autoload.php');
$id = $_GET['id'];

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}


$user = $authentication->getUserFromSession();

if ($user->getRole() == 'Administrateur') {


    if (isset($_POST['OrdonneesVerte'])) {
        $seuilBas = (int)$_POST['OrdonneesVerte'];
    }

    if (isset($_POST['OrdonneesRouge'])) {
        $seuilHaut = (int)$_POST['OrdonneesRouge'];
    }

    if (isset($_POST['nomBleu'])) {
        $nomBleu = $_POST['nomBleu'];
    }

    if (isset($_POST['nomRouge'])) {
        $nomRouge = $_POST['nomRouge'];
    }


    if (isset($_POST['nomOrange'])) {
        $nomOrange = $_POST['nomOrange'];
    }


    if (isset($_POST['nomVert'])) {
        $nomVert = $_POST['nomVert'];
    }

    if (isset($_POST['nomOrdonneeGauche'])) {
        $nomOrdonneeGauche = $_POST['nomOrdonneeGauche'];
    }

    if (isset($_POST['nomOrdonneeDroite'])) {
        $nomOrdonneeDroite = $_POST['nomOrdonneeDroite'];
    }

    if (isset($_POST['abscisse'])) {
        $abscisse = $_POST['abscisse'];
    }


    ConfigGraph::UpdateConfig(1, $seuilBas, $seuilHaut, $nomBleu, $nomRouge, $nomVert, $nomOrange, $nomOrdonneeGauche, $nomOrdonneeDroite, $abscisse);



}
header("Location: ../lot.php?id=$id");
exit();