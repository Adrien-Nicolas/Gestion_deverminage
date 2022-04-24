<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);


require_once ('php/autoload.php');

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$page = new WebPage("EPL - Création d'un utilisateur");
$page->addAuthor('De Buyser Rémi');
$page->addAuthor('Nicolas Adrien');


$page->appendContent(<<<HTML

    
    <div class="container">
        <h1 class="main-h1">Création d’un compte</h1>
HTML);
if(isset($_GET["error"])) {
    $message = $_GET["error"];

    $page->appendContent(<<<HTML
<span id="error" class="cred">$message</span>
HTML
    );
} else {
    $page->appendContent(<<<HTML
<span id="error" class="cred"></span>
HTML
    );
}
$page->appendContent(<<<HTML
        <form action ="php/verifyUser.php" method="POST" class="form-grid">
        <div>
            <span>Nom :</span>
            <input type="text" placeholder="Entrez un nom" name="lastname">
        </div>
        <div>
            <span>Prénom :</span>
            <input type="text" placeholder="Entrez un prénom" name="firstname">
        </div>
        <div>
            <span>Adresse email :</span>
            <input type="email"placeholder="Entrez une adresse email" name="email">
        </div>
            <div>
                <span>Nom d'utilisateur :</span>
                <input type="text"placeholder="Entrez un nom d'utilisateur" name="nickname">
            </div>
        <div>
            <span>Rôle :</span>
            <select name="role">
                <option>Administrateur</option>
                <option selected>Operateur</option>
            </select>
        </div>
            <span></span>
        <div>
            <span>Mot de passe :</span>
            <input type="password" placeholder="Entrez un mot de passe" id="myPswd" name="pass">
        </div>
        <div>
            <span>Confirmation du mot de passe :</span>
            <input type="password" placeholder="Confirmez le mot de passe" id="myPswd2" name="pass2">
        </div>
        <div class="grid-t2 item-center">
            <input type="checkbox" onclick="myFunction()" id="showpsd">
            <label for="showpsd">Afficher les mots de passe</label>
        </div>
        <div class="btn-create grid-t2 item-center">
            <input type="submit" value="Créer le compte" name="submit">
        </div>
    </form>
    </div>
    <script>
        function myFunction() {
          var x = document.getElementById("myPswd");
          var x2 = document.getElementById("myPswd2");
          if (x.type === "password") {
            x.type = "text";
            x2.type = "text";
          } else {
            x.type = "password";
            x2.type = "password";
          }
        }
    </script>
    
    
    
    <script type="module">
import {AjaxRequest} from  './js/AjaxRequest.js';

function ajaxAddError() {
    new AjaxRequest({
        url: "php/ErrorUser.php",
        method: "post",
        parameters: {
            mdp: document.getElementsByName('pass')[0].value,
            mdp2 : document.getElementsByName('pass2')[0].value
        },
        onSuccess: function (res) {
            document.getElementById('error').innerHTML = res;
        }, onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    });
}

var input1 = document.getElementsByName('pass')[0];
var input2 = document.getElementsByName('pass2')[0];

input1.addEventListener('keyup', function() {
    ajaxAddError();
});

input2.addEventListener('keyup', function() {
    ajaxAddError(); 
});



input1.addEventListener('change', function() {
    ajaxAddError();   
});

input2.addEventListener('change', function() {
    ajaxAddError();
});

</script>
HTML
);

echo $page->toHTML();