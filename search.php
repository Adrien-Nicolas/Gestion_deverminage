<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('php/autoload.php');

$page = new WebPage("EPL - Recherche");
$page->addAuthor('De Buyser Rémi');
$page->appendCssUrl('./css/index.css');

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$user = $authentication->getUserFromSession();
if (isset($_GET["search"]))
{

    $_GET["search"] = htmlspecialchars($_GET["search"]);
    $terme =  $_GET["search"];
    $terme = trim($terme);
    $terme = strip_tags($terme);
    $terme = strtolower($terme);

    $listCap = MyPDO::getInstance()->prepare("SELECT SN FROM Capteur WHERE SN LIKE :terme ");
    $listCap->bindValue(':terme', "%{$terme}%");
    $listCap->execute();

    $listEssai = MyPDO::getInstance()->prepare("SELECT id FROM Essai WHERE id LIKE :terme2 ");
    $listEssai->bindValue(':terme2', "%{$terme}%" );
    $listEssai->execute();

    $listLot = MyPDO::getInstance()->prepare("SELECT numLot FROM Lot WHERE numLot LIKE :terme3 ");
    $listLot->bindValue(':terme3',  "%{$terme}%" );
    $listLot->execute();
}

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
    <h1 class="main-h1">Resultats de votre recherche</h1>
    <div>

<h2 class="main-h2"> Produit n°Série :</h2>
<div class="grid-line">
        <p class="titleRange grid-line-3">Rafraîchissement des graphiques : </p>
        <label class="switch">
            <input id="slide" type="checkbox" checked="">
            <span class="slider round"></span>
        </label>
    </div>

HTML);
$html = "";
    while($capFind = $listCap->fetch(PDO::FETCH_ASSOC))
{
    $cap = Capteur::getCapteurFromSN($capFind["SN"]);
    $valid = $cap->getValid();
    $i = $cap->getId();
    $SN = $cap->getSN();
    $idE = $cap->getidEssai();
    $lot = Lot::getLotFromID($cap->getIdLot());
    $type = $lot->getProduct();
    $pos = $cap->getPosition();
    $position = $emplacement[$pos-1];


    $html .= <<<HTML
<div class="lot">
                <div class="nlot cpointer" id="btnTube$i">
                    <a>$type n°série : <span class = "bold"> $SN </span>  dans l'essai <span class = "bold"> $idE  </span> à l'emplacement  <span class = "bold">$position </span></a>

HTML;
    if ($valid == 0) {
        $html .= '<span class="nvalid bold">non conforme &#10007;</span>';
    } else {
        $html .= '<span class="valid bold"> conforme &#10003;</span>';
    }
    $html .= <<<HTML
                    <div class="lot-icon" id="iconBtnTube$i">
                        <span class="iconify" data-icon="bi:chevron-down" data-inline="false"></span>
                    </div>
                </div>
                <div class="tube dnone" id="tube$i">
                    <div id="graph$i"></div>
                </div>
            </div>
            <script type="module">

                import {AjaxRequest} from  './js/AjaxRequest.js';

                /**
                 * Permet d'ajouter le graphique
                 */

                var tube$i = document.getElementById('tube$i');
                var btnTube$i = document.getElementById('btnTube$i');
                var IconBtnTube$i = document.getElementById('iconBtnTube$i');

                function ajaxAddGraph() {
                    new AjaxRequest({
                        url: "php/getGraph.php",
                        method: "post",
                        parameters: {
                            id: $i,
                            valid: $valid,
                            refresh: document.getElementById('slide').value
                        },
                        onSuccess: function (res) {
                            var divtube = document.createElement('div');
                            divtube.innerHTML = res;
                            tube$i.appendChild(divtube);
                        }, onError: function (status, message) {
                            window.alert('Error ' + status + ': ' + message);
                        }
                    });
                }

                btnTube$i.addEventListener("click", function () {
                    if (!tube$i.classList.contains("dnone")) {
                        tube$i.classList.add("dnone");
                        IconBtnTube$i.classList.remove("iconBtnRotate");
                        IconBtnTube$i.classList.add("iconBtnRotateNone");
                        tube$i.innerHTML = "";
                    }
                    else {
                        tube$i.classList.remove("dnone");
                        IconBtnTube$i.classList.add("iconBtnRotate");
                        IconBtnTube$i.classList.remove("iconBtnRotateNone");
                        ajaxAddGraph();
                    }
                });
            </script>
HTML;

}
$page->appendContent($html);
$page->appendContent(<<<HTML
<h2 class="main-h2"> Numéro d'essai :</h2>
<div class="index-item-title index-title">
            <p>N° d'essai</p>
            <p>début le : </p>
            <p>dernière valeur le : </p>
            <p>conformité</p>
        </div>
HTML
);

while($essaiFind = $listEssai->fetch(PDO::FETCH_ASSOC))
{
    $ret = Essai::getEssaiFromId($essaiFind["id"]);
    $id = $ret->getId();
    $date = $ret->getDateString();
    $coHTML = $ret->getValidHTML();
    $co = $ret->getValid();
    $class = '';
    if ($co == 0) {
        $class = 'cred';
    }
    $lastval = $ret->getLastCapDateString();

    $page->appendContent(<<<HTML
        <div class="index-item" >
            <div class="index-item-info cpointer $class" id = "$id" >
                <p> Essai n°$id </p>
                <p> $date </p>
                <p> $lastval </p>
                  $coHTML
            </div>
HTML
    );

    if ($ret->getConfig() == 0 || $user->getRole() == 'Administrateur') {
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
        $lcap = $ret->getLastCap($idLot);
        $firstCap = $emplacement[$fcap-1];
        $lastCap = $emplacement[$lcap-1];

        $valLot = $lot->getValidHTML();
        $page->appendContent("<a href='lot.php?id=$idLot' class='cblack ahref grid-index txtc'><p>Lot n°$numlot</p><p>$firstCap</p><p>$lastCap</p>$valLot</a>");
    }

    $page->appendJs(<<<JS
var div$id = document.getElementById('$id');
var affiche$id = document.getElementById('div$id');

div$id.addEventListener('click', function () {
    if (affiche$id.classList.contains('dnone')) {
        affiche$id.classList.remove('dnone');
        affiche$id.classList.add('anim');
    }
    else {
        affiche$id.classList.add('dnone');
        affiche$id.classList.remove('anim');
    }
})
JS
    );
    $page->appendContent('</div>');

}

$page->appendContent(<<<HTML
<h2 class="main-h2"> Numéro de lot :</h2>
HTML);

while($lotFind = $listLot->fetch(PDO::FETCH_ASSOC))
{
    $lot = Lot::getLotFromNum($lotFind["numLot"]);
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
        $posit = $emplacement[$position-1];
        $page->appendContent(<<<HTML
    <div class="lot-info">
        <p>Numéro de Série : <span class="bold">$sn</span></p>
        <p>Dans l'essai <span class="bold" >$idEssai </span> à la position <span class="bold" > $posit</span></p>
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


$page->appendContent(<<<HTML
    </div>
</div>
HTML);


echo $page->toHTML();

