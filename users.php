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

$page = new WebPage("EPL - Gestion des comptes");
$page->addAuthor('De Buyser Rémi');

$page->appendContent(<<<HTML
<div class="container">
        <h1 class="main-h1">Liste des comptes</h1>
HTML);
if(isset($_GET["class"] ) && isset($_GET["message"] )) {
    $class = $_GET["class"];
    $message = $_GET["message"];

    $page->appendContent(<<<HTML
<span class="$class">$message</span>
HTML
    );
}
$page->appendContent(<<<HTML
        <div class="grid-users-container">
            <p class="grid-title">Nom Prénom</p>
            <p class="grid-title">Nom d'utilisateur</p>
            <p class="grid-title">Rôle</p>
            <p></p>
HTML
);
$users = Utilisateur::getAllUsers();
$ret = 0;
foreach ($users as $user) {
    $id = $user->getId();
    $fn = $user->getFirstName();
    $ln = $user->getLastName();
    $nn = $user->getNickName();
    $mm = $user->getEmail();
    $role = $user->getRole();
    $ret +=1;
    $ret1 = $ret+1;
    $page->appendContent(<<<HTML
            <p>$ln  $fn </p>
            <p>$nn</p>
            <p>$role</p>
            <a id='$ret' class="cpointer">modifier</a>
            <div class="grid-t4 dnone" id="div$ret">
                <form action ="/php/SetUser.php?id=$id" method="POST" class="form-grid">
                    <div>
                        <span>Nom :</span>
                        <input name="nom" type="text" placeholder="Entrez un nom" value="$ln">
                    </div>
                    <div>
                        <span>Prénom :</span>
                        <input name="prenom" type="text" placeholder="Entrez un prénom" value="$fn">
                    </div>
                    <div>
                        <span>Nom d'utilisateur :</span>
                        <input name="nickname" type="text"placeholder="Entrez un nom d'utilisateur" value="$nn">
                    </div>
                    <div>
                        <span>Rôle :</span>
                        <select name = "role">
HTML);

    if ($role=='Administrateur') {
        $page->appendContent('<option selected>Administrateur</option><option>Opérateur</option>');
    } else {
        $page->appendContent('<option>Administrateur</option><option selected>Opérateur</option>');
    }

$page->appendContent(<<<HTML
                        </select>
                    </div>
                   <div>
                <span>Adresse email :</span>
                <input type="email"placeholder="Entrez une adresse email" name="email" value="$mm">
                 </div>
                  <div>
                    <span></span>
                    </div>
                    <div>
                        <span>Mot de passe :</span>
                        <input name= "mdp" type="password" placeholder="Entrez un mot de passe" id="myPswd$ret">
                    </div>
                    <div>
                        <span>Confirmation du mot de passe :</span>
                        <input name= "mdp2" type="password" placeholder="Confirmez le mot de passe" id="myPswd$ret1">
                    </div>
                    <div class="grid-t2 item-center">
                        <input type="checkbox" onclick="myFunction$ret()" id="showpsd$ret">
                        <label for="showpsd$ret">Afficher les mots de passe</label>
                    </div>
                    <div class="grid-item2">
                        <div class="btn-create item-center">
                            <input type="submit" value="Modifier le compte">
                        </div>
                        <a href="/php/DeleteUser.php?id=$id" class="btn-form m-aBtn">Supprimer le compte</a>
                    </div>
                </form>
            </div>
            <script>
                var btndiv$ret = document.getElementById('$ret');
                var div$ret = document.getElementById('div$ret');

                btndiv$ret.addEventListener('click', function () {
                    if(div$ret.classList.contains('dnone')){
                        div$ret.classList.remove('dnone');
                    } else {
                        div$ret.classList.add('dnone');
                    }
                });
            </script>
            <script>
                function myFunction$ret() {
                    var x = document.getElementById("myPswd$ret");
                    var x2 = document.getElementById("myPswd$ret1");
                    if (x.type === "password") {
                        x.type = "text";
                        x2.type = "text";
                    } else {
                        x.type = "password";
                        x2.type = "password";
                    }
                }
            </script>
HTML
    );
    $ret++;
}
$page->appendContent(<<<HTML
        </div>
    </div>
HTML
);
echo $page->toHTML();