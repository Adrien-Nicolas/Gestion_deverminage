<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once("./php/autoload.php");

$page = new WebPage("EPL - Statistiques");
$page->addAuthor("De Buyser Rémi");
$page->appendCssUrl("./css/stats.css");

$listPerUser = Utilisateur::getNbEssaiPerUser();
$listPos = Capteur::getStatsPerPosition();
$listPerProduct = Lot::getNbLotPerProduct();
$listErrorPerLot = Lot::getNbErrorPerLot();

$page->appendContent("<div class='container'>");

$page->appendContent(<<<HTML
<h1 class="main-h1"> Nombre d'essais effectués par opérateur</h1>
<div class='grid-stats'>
HTML);
$page->appendContent("<p class='bold'>Nom :</p><p class='bold'>Prénom :</p><p class='bold'>Nombre d'essai :</p>");
foreach ($listPerUser AS $result) {
    $page->appendContent("<p>".$result["lastname"]." </p><p>".$result["firstname"]." </p><p>".$result["count"]."</p>");
}

$page->appendContent("</div>");


$page->appendContent(<<<HTML
        <h1 class="main-h1"> Nombre d'erreurs par position de capteur</h1>
        <h3 class="txtc">Enceinte climatique</h3>
        <div class="flex-center">
        <div class="enceinte">
HTML);
$positionCount = 61;
$listLetter = ['H', 'G', 'F', 'E', 'D', 'C', 'B', 'A'];
for ($i = 0; $i < 8; $i += 2) {
    $positionCount -= 8;
    $letter = $listLetter[$i];
    $page->appendContent('<div class="grid-8" ><p class="grid-item-letter">' . $letter . '</p>');
    for ($j = 0; $j < 8; $j++) {
        $positionAct = $positionCount + $j;
        $j1 = $j + 1;
        $page->appendContent(<<<HTML
        <div class="grid-item-led" >
            <p class="led">$j1</p>
HTML
        );
        if(isset($listPos["$positionAct"])){
            $page->appendContent('<p class="number cred">'.$listPos["$positionAct"].'</p>');
        } else {
            $page->appendContent('<p class="number cgreen bold">0</p>');
        }
        $page->appendContent('</div>');
    }
    $page->appendContent('</div>');
    $positionCount -= 7;
    $letter = $listLetter[$i + 1];
    $page->appendContent('<div class="grid-7" ><p class="grid-item-letter">' . $letter . '</p>');
    for ($j = 0; $j < 7; $j++) {
        $positionAct = $positionCount + $j;
        $j1 = $j + 1;
        $page->appendContent(<<<HTML
        <div class="grid-item-led" >
            <p class="led">$j1</p>
HTML
        );
        if(isset($listPos["$positionAct"])){
            $page->appendContent('<p class="number cred">'.$listPos["$positionAct"].'</p>');
        } else {
            $page->appendContent('<p class="number cgreen bold">0</p>');
        }
        $page->appendContent('</div>');
    }
    $page->appendContent('</div>');
}



$page->appendContent("</div>");

$page->appendContent("</div>");



$page->appendContent(<<<HTML
<h1 class="main-h1"> Nombre de lots en fonction du type de produit</h1>
<div class='grid-stats'>
HTML);
$page->appendContent("<p class='bold'>Produits :</p><p class='bold'></p><p class='bold'>Nombre de lots :</p>");
foreach ($listPerProduct AS $value) {
    $page->appendContent("<p>".$value["product"]."</p><p></p><p>".$value["count"]."</p>");
}

$page->appendContent("</div>");



$page->appendContent(<<<HTML
<h1 class="main-h1"> Nombre de capteurs défaillants en fonction du numéro de lot</h1>
<div class='grid-stats'>
HTML);
$page->appendContent("<p class='bold'>Numéro de Lot :</p><p class='bold'></p><p class='bold'>Nombre de capteurs défaillants :</p>");
foreach ($listErrorPerLot AS $erreur) {
  if ($erreur['numLot'] != 'NULL') {
      $page->appendContent("<p>" . $erreur["numLot"] . "</p><p></p><p>" . $erreur["count"] . "</p>");
  }
}

$page->appendContent("</div>");
$page->appendContent("</div>");





echo $page->toHTML();