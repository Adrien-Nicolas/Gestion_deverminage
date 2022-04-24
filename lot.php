<?php

require_once('php/autoload.php');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once ('php/src/WebPage.php');

$id = $_GET['id'];
$lot = Lot::getLotFromID($id);

if ($lot->getId() == 0) {
echo '<script language="Javascript">
document.location.replace("./");
</script>';
}


$conf = ConfigGraph::getConfigFromId(1);
$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}
$user = $authentication->getUserFromSession();

if ($user->getRole() == 'Administrateur') {

    $idConf = $conf->getId();
    (int)$seuilBas = $conf->getseuilBas();
    (int)$seuilHaut = $conf->getseuilHaut();
    $nomBleu = $conf->getnomBleu();
    $nomRouge = $conf->getnomRouge();
    $nomVert = $conf->getnomVert();
    $nomOrange = $conf->getnomOrange();
    $nomOrdonneeGauche = $conf->getnomOrdonneeGauche();
    $nomOrdonneeDroite = $conf->getnomOrdonneeDroite();
    $abscisse = $conf->getAbscisse();

}


$capteurs = $lot->getAllCapteurs();
$nbValid = $lot->getNbValidCap();
$nbNotValid = $lot->getNbNotValidCap();
$nbCap = $lot->getNbCap();
$essaisId = $lot->getAllEssaisId();
$desc = $lot->getDescription();
$idlist = '';
$type = $lot->getProduct();




foreach ($essaisId AS $eId) {
    $idlist.=$eId.' ,';
}
$idlist = substr($idlist, 0, -1);


$page = new WebPage("EPL - Gestion des lots");
$page->addAuthor('De Buyser Rémi');
$page->addAuthor('Nicolas Adrien');
$page->appendJsUrl("https://cdn.plot.ly/plotly-1.8.0.min.js");

$numlot = $lot->getNumLot();


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
<h1 class="h1-lot">Lot n°$numlot <a href="php/testPDF.php?id=$id" class="pdf ahref">PDF <span class="iconify" data-icon="fluent:document-pdf-20-regular" data-inline="false"></span></a></h1>
HTML);
if ($user->getRole() == 'Administrateur') {
    $page->appendContent(<<<HTML
<div class="flex-center">
    <a class="ahref cpointer modal-btn-config" id="modal-btn-config">Configuration du graphique</a>
    <span class="iconify" data-icon="la:hand-pointer" data-inline="false"></span>
</div>
HTML);

}
$page->appendContent(<<<HTML
<div class="grid-container" >
    <p class="txtc">Nombre de $type essayés : $nbCap </p>
    <p class="txtc">Dans les essais : $idlist</p>
    <p class="txtc">Nombre de $type conformes : $nbValid</p>
    <p class="txtc">Nombre de $type non conformes : $nbNotValid</p>

HTML
);

$page->appendContent(<<<HTML
    <div class="grid-line">
       <p class="titleRange grid-line-3">Rafraîchissement des graphiques : </p>
        <label class="switch">
            <input id="slide" type="checkbox" checked="">
            <span class="slider round"></span>
        </label>
    </div>
HTML
);


if ($user->getRole() == 'Administrateur') {
    $page->appendContent('<a class="cpointer t-end" id="modal-btn">Voulez vous supprimer ce lot ?</a>');
}
$page->appendContent(<<<HTML
</div>
HTML
);
if ($user->getRole() == 'Administrateur') {
    $page->appendContent(<<<HTML
    <form action="php/SetLot.php?id=$id" method="POST" id="form-create" class="form-textarea">
         <label >Description/Commentaire :</label>
         <textarea  class="textarea" name="descLot" id="textarea" >$desc</textarea>
         <div class="btn-create">
            <input class="cpointer" type="submit" value="Modifier">
         </div>
    </form>
 HTML
    );
}else {
    $page->appendContent(<<<HTML
      <p class="txtc">Description/Commentaires :</p>    
  <p> $desc</p>
HTML
    );
}
    $page->appendContent(<<<HTML
<div class="container-lot">
HTML
);

foreach ($capteurs as $cap) {
    $valid = $cap->getValid();
    $i = $cap->getId();
    $SN = $cap->getSN();
    $position = $cap->getPosition();

    $emp = $emplacement[$position - 1];



        $page->appendContent(<<<HTML
    <div class="lot">
                <div class="nlot cpointer" id="btnTube$i">
                    <a>$type n°serie :  $SN </a>
                    <a> Emplacement : <span class="bold">$emp</span></a>
HTML
        );
        if ($valid == 0) {
            $page->appendContent(<<<HTML
    <span class="nvalid bold">non conforme &#10007;

                  </span>
    HTML
            );
        } else {
            $page->appendContent(<<<HTML
    <span class="valid bold"> conforme &#10003;
                  </span>
  HTML
            );
        }
        $page->appendContent(<<<HTML
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
            refresh: Number(document.getElementById("slide").checked)

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
HTML
        );
    
}

$page->appendContent(<<<HTML
        </div>
    </div>
HTML
);


    if ($user->getRole() == 'Administrateur') {

        $page->appendContent(<<<HTML
    <div class="modaldel-bg dnone">
        <div class="modaldel">
            <p id="modal-p">Etes vous sur de vouloir supprimer ce lot ?</p>
            <div id="modal-div">
                <a href="php/RemoveToDB.php?id=$id">Oui</a>
                <a class="cpointer" id="modal-close">Non</a>
            </div>
        </div>
    </div>
    
    <script>
        var modalClose = document.getElementById('modal-close');
        var modalBtn = document.getElementById('modal-btn');
        var modalBg = document.querySelector('.modaldel-bg');

        modalBtn.addEventListener('click', function () {
            modalBg.classList.remove('dnone');
        })

        modalClose.addEventListener('click', function () {
            modalBg.classList.add('dnone');


        })

        var modal = document.querySelector('.modaldel');
        var modalp = document.getElementById('modal-p');
        var modaldiv = document.getElementById('modal-div');

        modalBg.onclick = function(e) {
            if(e.target !== modal && e.target !== modaldiv && e.target !== modalp) {
                modalBg.classList.add('dnone');
            }
        }
        
        
    </script>
HTML);
}
if ($user->getRole() == 'Administrateur') {
    $page->appendContent(<<<HTML
    <div class="modal-bg-config">
    
             <div class="modal-config">
                <h1 class="main-h1">Configuration des graphiques</h1>
                <form action ="/php/SetConfig.php?id=$id" method="POST" class="form-grid">
                 
                        <div>
                            <p>Courbe verte (Seuil Bas) :</p>
                            <label for="nameRed">Nom de la ligne</label>
                            <input type="text" placeholder="Entrez un nom" id="nameRed" value="$nomRouge" name= "nomRouge">
                            <label for"valueRed">Valeur de la ligne</label>
                            <input type="number" placeholder="Entrez une ordonnée" id="valueRed" value=$seuilHaut name="OrdonneesRouge" min="1" max="1000">
                        </div>
                        <div>
                            <p>Courbe rouge (Seuil Haut) :</p>
                            <label for="nameGreen">Nom de la ligne</label>
                            <input type="text" placeholder="Entrez un nom" id="nameGreen" value="$nomVert" name="nomVert">
                            <label for"valueGreen">Valeur de la ligne</label>
                            <input type="number" placeholder="Entrez une ordonnée" id="valueGreen" value="$seuilBas" name="OrdonneesVerte" min="1" max="1000">
                        </div> 
                        <div>
                            <p>Courbe orange (Température) :</p>
                            <label for="nameOrange">Nom de la courbe orange</label>
                            <input type="text" placeholder="Entrez un nom" id="nameOrange" value="$nomOrange" name="nomOrange">
                        </div>
                         <div>
                            <p>Courbe bleue (Intensité lumineuse) :</p>
                            <label for="nameBlue">Nom de la courbe bleu</label>
                            <input type="text" placeholder="Entrez un nom" id="nameBlue" value="$nomBleu" name= "nomBleu" >
                        </div>
                        <div>
                            <p>Ordonnée gauche</p>
                            <label for="nomOrdonneeGauche">Nom de l'ordonnée gauche</label>
                            <input type="text" placeholder="Entrez un nom" id="nomOrdonneeGauche" value="$nomOrdonneeGauche" name="nomOrdonneeGauche">
                        </div>
                        <div>
                            <p>Ordonnée droite</p>
                            <label for="nomOrdonneeDroite">Nom de l'ordonnée Droite</label>
                            <input type="text" placeholder="Entrez un nom" id="nomOrdonneeDroite" value="$nomOrdonneeDroite" name="nomOrdonneeDroite">
                        </div>
                        <div>
                            <p>Abscisse</p>
                            <label for="Abscisse">Nom de l'abscisse</label>
                            <input type="text" placeholder="Entrez un nom" id="abscisse" value="$abscisse" name="abscisse">
                        </div>
                        <div class="flex-center grid-t2 m-0">
                            <input class="validBTN cpointer" type="submit" value="Valider">
                        </div>
                </form>
                
                
                <span class="modal-close-config">X</span>
            </div>
        </div>
        <script>
            var modalCloseConfig = document.querySelector('.modal-close-config');
            var modalBtnConfig = document.getElementById('modal-btn-config');
            var modalBgConfig = document.querySelector('.modal-bg-config');
            var modalConfig = document.querySelector('.modal-config');
                    
            modalBtnConfig.addEventListener('click', function (){
                modalBgConfig.classList.add('modal-active-config');
            })
                    
            modalCloseConfig.addEventListener('click', function (){
               modalBgConfig.classList.remove('modal-active-config');
            })
            
            modalBgConfig.onclick = function(e) {
                if (e.target.classList.contains('modal-bg-config')) {
                    modalBgConfig.classList.remove('modal-active-config');
                }
            }
        </script>
HTML
    );
}
$page->appendContent(<<<HTML
</body>
</html>
HTML
);

echo $page->toHTML();
