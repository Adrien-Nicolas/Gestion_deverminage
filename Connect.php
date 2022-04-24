<?php
require "php/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

try {
    $authentication = new SecureUserAuthentication();
// Tentative de connexion
    $user = $authentication->getUserFromAuth();
    if($authentication->isUserConnected()){
        header('Location: ../login.php?erreur=Bravo');
    }
} catch (AuthenticationException $e) {
    header("Location: ../login.php?erreur=Votre pseudo ou mot de passe est incorect");
} catch (Exception $e) {
    header('Location: ../login.php?erreur=Une erreur est survenue veuillez essayer à nouveau ou contacter un administrateur');
}
