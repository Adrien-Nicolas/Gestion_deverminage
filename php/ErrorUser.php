<?php

require_once("autoload.php");

$msg = "";

if (isset($_POST["mdp"]) && isset($_POST["mdp2"])) {
   if( $_POST["mdp"] != $_POST["mdp2"] &&  !($_POST["mdp"] == "" || $_POST["mdp2"] == "")) {
       $msg = "Les mots de passes ne sont pas identiques";
   }
}


echo $msg;