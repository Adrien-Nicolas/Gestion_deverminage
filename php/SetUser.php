<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once ('autoload.php');

$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$user = $authentication->getUserFromSession();




    if ($user->getRole() == 'Administrateur') {
        $id = $_GET['id'];


        if (isset($_POST["nom"]) && isset($_POST["prenom"]) &&
            isset($_POST["email"]) && isset($_POST["nickname"]) &&
            isset($_POST["role"]) && isset($_POST["role"]) &&
            isset($_POST["mdp"]) && isset($_POST["mdp2"])

        ) {
            $lName = $_POST["nom"];
            $fName = $_POST["prenom"];
            $mail = $_POST["email"];
            $nName = $_POST["nickname"];
            $role = $_POST["role"];
            $pass = $_POST["mdp"];
            $pass2 = $_POST["mdp2"];
            if($pass == $pass2) {
                $pass = hash('sha512', $pass);
                Utilisateur::updateUser($id, $fName, $lName, $mail, $role, $nName, $pass);
                header('location: ../users.php?message=Le compte a bien été modifié&class=cgreen');
                exit();
            } else {
                header('location: ../users.php?message=Les mots de passes ne sont pas identiques&class=cred');
                exit();
            }
        }

    } else {
        header("Location: ../users");
        exit();
    }

