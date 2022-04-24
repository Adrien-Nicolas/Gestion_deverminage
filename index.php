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
$page->appendCssUrl('css/index.css');


$user = $authentication->getUserFromSession();
$current =0;

$emplacement = [
    'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7',
    'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8',
    'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7',
    'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8',
    'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7',
    'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8',
    'G1', 'G2', 'G3', 'G4', 'G5', 'G6', 'G7',
    'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'H7', 'H8'
];




$page->appendContent(<<<HTML
<div class="container">
    <div class="tab-btn-container">
        <div class="tab-btn tab-active bold" id="btn-essais">Essais</div>   
        <div class="tab-btn" id="btn-lots">Lots</div>   
    </div>
    <div id="container-essais" class="">
        <h1 class="main-h1">Liste des essais</h1>
        
        <p>Cliquez sur un essai pour en voir les détails</p>
        <div class="index-item-title index-title">
            <p>N° d'essai</p>
            <p>début le : </p>
            <p>dernière valeur le : </p>
            <p>conformité</p>
        </div>
HTML
);
$essai = Essai::getAllEssais();
foreach ($essai as $ret) {
    $id = $ret->getId();
    $date = $ret->getDateString();
    $coHTML = $ret->getValidHTML();
    $co = $ret->getValid();
    $nbval = $ret->getNbVal();
    $class = '';
    if ($co == 0) {
        $class = 'cred';
    }
    $lastval = $ret->getLastCapDateString();
    $lastEssai = Essai::getlastEssai();


    if($lastEssai == $id && $ret->getConfig() == 0) {
        $page->appendContent(<<<HTML
        <div class="index-item new" >       
            <span class="nouveau bold cpointer">New </span>
            <div class="index-item-info cpointer $class" id = "$id" >
                <p> Essai n°<span class="bold">$id</span>  </p>
                <p> $date </p>
                <p> $lastval </p>
                  $coHTML
            </div>
HTML
        );
    }elseif($lastEssai == $id && $ret->getConfig() == 1){
        $page->appendContent(<<<HTML
        <div class="index-item current">
          <span class="nouveau bold cpointer">En cours</span>
            <div class="index-item-info cpointer $class" id = "$id" >
                <p> Essai n°<span class="bold">$id</span>  </p>
                <p> $date </p>
                <p> $lastval </p>
                  $coHTML
            </div>
HTML
        );
    }else{
        $page->appendContent(<<<HTML
        <div class="index-item last" >
            <div class="index-item-info cpointer $class" id = "$id" >
                <p> Essai n°<span class="bold">$id</span>  </p>
                <p> $date </p>
                <p> $lastval </p>
                  $coHTML
            </div>
HTML
        );
    }

    if (($ret->getConfig() == 0 || $nbval<1) || $user->getRole() == 'Administrateur' ) {

        $page->appendContent(<<<HTML
            <div class="index-mod" >
                <a href = "essai.php?idessai=$id" class="ahref" >modifier</a>
            </div>
HTML
        );
    }else{
        $page->appendContent(<<<HTML
 <div></div>

HTML
        );
    }

        $page->appendContent(<<<HTML
        </div>
        <div class="dnone" id = "div$id" >
HTML
    );
    $notVCap = $ret->getNotValidCap();
    if(sizeof($notVCap) > 0) {
        $page->appendContent('<p>Liste des positions où il y a une erreur : ');
        foreach ($notVCap AS $cap) {
            $page->appendContent($emplacement[$cap-1].', ');
        }
    }

    $page->appendContent(<<<HTML
            <div class="grid-index">
                <p class="index-title txtc" >Numéro de lot</p>
                <p class="index-title txtc empdeb" />
                <p class="index-title txtc empfin" />
                <p class="index-title txtc" > Conformité</p>
            </div>
HTML
    );
    $lots = $ret->getLots();
    foreach ($lots as $lot) {
        $numlot = $lot->getNumLot();
        $idLot = $lot->getId();
        $fcap = $ret->getFirstCap($idLot);
        $firstCap = $emplacement[$fcap-1];
        $lcap = $ret->getLastCap($idLot);
        $lastCap = $emplacement[$lcap-1];
        $valLot = $lot->getValidHTML();
        $page->appendContent("<a href='lot.php?id=$idLot' class='cblack ahref grid-index txtc'><p>Lot n°$numlot</p><p>$firstCap</p><p>$lastCap</p>$valLot</a>");
    }

    $page->appendJs(<<<JS
var div$id = document.getElementById('$id');
var div1$id = document.getElementById('div$id');

div$id.addEventListener('click', function () {
    if (div1$id.classList.contains('dnone')) {
        div1$id.classList.remove('dnone');
        div1$id.classList.add('anim');
    }
    else {
        div1$id.classList.add('dnone');
        div1$id.classList.remove('anim');
    }
})
JS
    );
    $page->appendContent('</div>');
}
$page->appendContent('</div>');


$page->appendContent(<<<HTML
<div class="dnone" id="container-lots">
<h1 class="main-h1">Liste des lots</h1>
        <p>Cliquez sur un lot pour en voir les détails</p>
HTML
);
$listLots = Lot::getAllLots();
foreach ($listLots as $lot) {
    $numlot = $lot->getNumLot();
    $conforme = $lot->getValidHTML();
    $id = $lot->getId();
    $nbCap = $lot->getNbCap();
    $nbNotVCap = $lot->getNbNotValidCap();
    $listEssai = $lot->getAllEssaisId();
    $desc = $lot->getDescription();
    $type = $lot->getProduct();
    $page->appendContent(<<<HTML
    <div class="index-lots">
        
        <a class="nlot ahref" href="lot.php?id=$id">
            <p class="cblack">Lot n°$numlot</p>
            <p>Se situe dans les essais :           
    HTML
    );
           foreach ($listEssai as $essai) {
               $page->appendContent(<<<HTML
          <span class="bold" >$essai</span>, 
HTML
               );
}
 $page->appendContent(<<<HTML
        </p>
            $conforme
        </a>
        <p>Ce lot contient <strong>$nbCap $type</strong> dont <strong>$nbNotVCap</strong> défaillant(s) et est contenu dans les essais : </p>
        <p class="underline">Description / Commentaire : </p>
        <span>$desc</span>
    
HTML
    );

    if ($nbNotVCap > 0) {
        $page->appendContent(<<<HTML
            <p class="showmore cpointer" id="btnLot$id"></p>
        HTML
        );
    }
    $page->appendContent(<<<HTML
    </div>
HTML
    );
    $listNotValidCap = $lot->getAllNotValidCapteurs();
    $page->appendContent(<<<HTML
<div class="dnone" id="lot$id">
HTML);
    foreach ($listNotValidCap as $cap) {
        $sn = $cap->getSN();
        $idEssai = $cap->getIdEssai();
        $position = $cap->getPosition();
        $emp = $emplacement[$position-1];

        $page->appendContent(<<<HTML
    <div class="lot-info">
        <p>Numéro de Série : <span class="bold"> $sn </span></p>
        <p>dans l'essai <span class="bold"> $idEssai </span> à la position <span class="bold"> $emp </span></p>
    </div>
HTML
        );
    }
    $page->appendContent(<<<HTML
</div>
HTML);
    if ($nbNotVCap > 0) {
        $page->appendJs(<<<JS
var btnLot$id = document.getElementById('btnLot$id'); 
var Lot$id = document.getElementById('lot$id');

btnLot$id.addEventListener('click', function () { 
    if (Lot$id.classList.contains('dnone')) {
        Lot$id.classList.remove('dnone');
        Lot$id.classList.add('anim');
    }
    else {
        Lot$id.classList.add('dnone');
        Lot$id.classList.remove('anim');
    }
 })
JS);
    }
}
$end_time = number_format(microtime(true) - $start_time, 2);
$page->appendContent(<<<HTML
</div>
HTML
);

$page->appendContent("<span>Votre page a été généré en $end_time seconde(s)</span></div>");
$page->appendJs(<<<JS
    var cLots = document.getElementById('container-lots');
    var cEssais   = document.getElementById('container-essais');
    
    var btnEssais   = document.getElementById('btn-essais');
    var btnLots   = document.getElementById('btn-lots');
    
    btnEssais.addEventListener( 'click', function () {
        btnLots.classList.remove('tab-active');
        btnLots.classList.remove('bold');
        btnEssais.classList.add('bold');
        btnEssais.classList.add('tab-active');
        
        cEssais.classList.add('active-lot');
        cEssais.classList.remove('dnone');
        cLots.classList.add('dnone');
    })
    
    btnLots.addEventListener( 'click', function () {
        btnEssais.classList.remove('tab-active');
        btnLots.classList.add('bold');
        btnEssais.classList.remove('bold');
        btnLots.classList.add('tab-active');
        
        cLots.classList.add('active-essai');
        cEssais.classList.add('dnone');
        cLots.classList.remove('dnone');
    })
JS
);


echo $page->toHTML();