<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('php/src/WebPage.php');
require_once('php/autoload.php');
date_default_timezone_set('Europe/Paris');

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$start_time = microtime(true);
$page = new WebPage("EPL - Administration Essai de déverminage");
$page->addAuthor('De Buyser Rémi');
$page->addAuthor('Nicolas Adrien');
$temp = Temperature::getlastTemp();

$user = $authentication->getUserFromSession();

$page->appendContent(<<<HTML
<div class="container">
        <h1 class="main-h1">Essai de deverminage</h1>
         <div class="grid-acceuil-container">
         <p class="bold txtc">Temperature</p>
         <p class="bold txtc">Temps depuis la mise en tension</p>
 <div class="item-grid-product txtc">
 <p>$temp</p> 
 </div>
 <div class="item-grid-product txtc">
 <p>12.25</p>
 </div>

 </div>
</div>
HTML
);

echo $page->toHTML();