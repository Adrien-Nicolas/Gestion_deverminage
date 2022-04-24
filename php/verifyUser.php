<?php
require_once ('autoload.php');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$user = $authentication->getUserFromSession();




if ($user->getRole() == 'Administrateur') {

        if ($_POST['submit']) {
            $lName = $_POST["lastname"];
            $fName = $_POST["firstname"];
            $mail = $_POST["email"];
            $nName = $_POST["nickname"];
            $role = $_POST["role"];
            $pass = $_POST["pass"];
            $pass2 = $_POST["pass2"];

            if ($pass == $pass2) {
                Utilisateur::createUser($lName, $fName, $mail, $nName, $role, hash('sha512', $pass), md5(time() . $nName));
                header('Location: ../users.php');
                exit();
            } else {
                header('Location: ../createuser.php?error=Les mots de passes ne sont pas identiques');
                exit();
            }
        } else {
            header('Location: ../createuser.php?error=Vous n\'avez rien envoyé');
            exit();
        }
} else {
    header('Location: ../index.php');
    exit();
}