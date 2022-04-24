<?php

require_once ('php/autoload.php');

$page = new WebPage("EPL - Connexion");
$page->addAuthor('De Buyser Rémi');
$page->appendCssUrl('./css/login.css');
$page->setLogin();

$authentication = new SecureUserAuthentication();

if ($authentication->isUserConnected()) {
    header('Location: ./');
    exit();
}

$login = $authentication->loginForm('./Connect.php');


$page->appendContent(<<<HTML

        <div class="container-login">
            <img class="logo-login" src="img/logo.png" alt="logo epl"/>
            <h1>Page de connexion</h1>
            <h2>Administration essai de déverminage</h2>
        
HTML);

$page->appendContent($login);

$page->appendContent(<<<HTML
        </div>
HTML);
$page->appendJs(<<<JS
            function showPassword() {
              var x = document.getElementById("password_input");
              if (x.type === "password") {
                x.type = "text";
              } else {
                x.type = "password";
              }
            }
JS);


echo $page->toHTML();