<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('php/src/WebPage.php');
require_once('./php/autoload.php');



$idEssai = $_GET["idessai"];



$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$user = $authentication->getUserFromSession();

$essai = Essai::getEssaiFromId($idEssai);

$nbval = $essai->getNbVal();


$userln = "ln";
$userfn = "fn";
if ($essai->getConfig()==1 && $user->getRole() == 'Operateur' && $nbval>0) {
    header('location: ./');
    exit();
}else{

    $listeProd = Lot::getAllProduits();


    $date = $essai->getDateString();
    $listLot = $essai->getLots();
    $listCapHS = $essai->getPosCapHS();




    $nbLot = sizeof($listLot);
    $listPers = Utilisateur::getAllUsers();



    $page = new WebPage("EPL - Gestion Essai");
    $page->addAuthor('De Buyser Rémi');

    $listPosition = [
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
<div class="container" style="width: 1500px;">
    <h1 class="main-h1">Essai n°$idEssai du $date</h1>
HTML
    );
    if ($user->getRole() == 'Administrateur') {

        $page->appendContent(<<<HTML
            <a class="cpointer Suppressai" id="modal-btn">Supprimer cet essai ?</a>
    HTML);
    }

    $option = "";
    foreach($listeProd AS $product) {
        $nameProduct = $product["NAME"];
        $idProd = $product["ID"];
        $option .="<option value=$idProd> $nameProduct </option>";
    }

    $page->appendContent(<<<HTML
    <div class="grid-essai">
        <form action="php/SetEssai.php?id=$idEssai" method="POST" id="form-create">
            <div class="warning"><span class="iconify" data-icon="emojione-v1:warning" data-inline="false"></span>
                Attention commencez par le groupe ayant le capteur le plus bas puis allez vers le plus élevé
            </div>
        

HTML
    );

    if (isset($_GET['error'])){
$error = $_GET['error'];
        $page->appendContent(<<<HTML
<div class="cred">$error</div>
HTML
        );



    }

    $page->appendContent(<<<HTML
            <div class="form-grid">
                <div class="grid-t2 w-100">
                    <span>Opérateur :</span>
HTML
    );

    if ($user->getRole() == "Administrateur") {
        $page->appendContent("<select form='form-create' name='idOp'>");
    } else {
        $page->appendContent("<select form='form-create' name='idOp' disabled>");
    }

    $idOp = $essai->getIdOp();

    if($idOp==0) {
        $page->appendContent("<option value='0' selected></option >");
    }else{
        $page->appendContent("<option value='0'></option >");
    }
    foreach($listPers AS $pers) {
        $idOperator = $pers->getId();
        $lastname = $pers->getLastname();
        $firstname = $pers->getFirstname();
        if ($idOp == $idOperator) {
            $page->appendContent(<<<HTML
<option value="$idOperator" selected>$lastname $firstname</option>
HTML);
        } else {
            $page->appendContent(<<<HTML
<option value="$idOperator">$lastname $firstname</option>
HTML);
        }
    }

    $capHS = "";
foreach ($listCapHS AS $item) {
    $capHS.= $listPosition[(int)$item["pos"]-1].", ";
}

    $page->appendContent(<<<HTML
                    </select>
                    <span>Entrez les capteur HS suivi du délimiteur "," :</span>
                    <input type="text" onkeyup="ajust()" onchange="ajust()" value="$capHS" placeholder="Emplacement des capteurs, par exemple : A1, B2" name="CapHS" id="capHS">
                </div>
HTML
    );
    $ret = 0;
    if ($nbLot == 0) {
        $page->appendContent(<<<HTML
                <p id="Groupe" class="grid-t2 Groupe">Groupe n°1</p>
                
                <div class="grid-t2 w-100">
                    <span>Type de produit :</span>
                    <select form='form-create' name='type1'>
HTML
        );

        $page->appendContent($option."</div>");


        $page->appendContent(<<<HTML
                <div class="grid-t2 m-0">
                    <span>Description / Remarque :</span>
                    <textarea class="textarea" name="desc1" placeholder="Entrez une description" ></textarea>
                </div>
                <span class="grid-t2"></span>
                <div>
                    <span>Capteur de début :</span>
                    <input type="text" onkeyup="ajust()" onchange="ajust()" placeholder="Numéro de capteur" value="A1" required id="capdeb1" maxlength="2" minlength="2" name="capDeb1">
                </div>
                <div>
                    <span>Capteur de fin :</span>
                    <input type="text" onkeyup="ajust()" onchange="ajust()" placeholder="Numéro de capteur" value="H8" required id="capfin1" maxlength="2" minlength="2" name="capFin1">
                </div>
                <div>
                    <span>Numéro de lot :</span>
                    <input type="text" placeholder="Numéro de lot" maxlength="8" minlength="8" required name="numLot1">
                </div>
                <div>
                    <span>Numéro de série de départ :</span>
                    <input type="text" placeholder="Numéro de série" value="" required name="SN1" id="SN1" maxlength="8" minlength="8">
                </div>
            </div>
            <div id="addForm">
HTML
        );
    }
    foreach ($listLot as $lot) {
        $ret++;
        $read = "";
        if ($ret < $nbLot) {
            $read = "readOnly";
        }
        $capDeb = $listPosition[$essai->getFirstCap($lot->getId()) - 1];
        $capFin = $listPosition[$essai->getLastCap($lot->getId()) - 1];
        $nameProduit = $lot->getProduct();


        $numLot = $lot->getNumLot();
        $desc = $lot->getDescription();
        $type = $lot->getProduct();
        $SNDeb = $essai->getSNBegin($numLot);



        if ($ret == 1) {
            $page->appendContent(<<<HTML
                <p id="Groupe" class="grid-t2 Groupe">Groupe n°$ret</p>
                <span class="cred" id="error1"></span>
                <span></span>
                <div class="grid-t2 w-100">
                    <span>Type de produit :</span>
                   
<select form='form-create' name='type1'>
HTML
            );

            foreach($listeProd AS $product) {
                $nameProduct = $product["NAME"];
                $idProd = $product["ID"];
                if($nameProduct == $nameProduit ) {
                    $page->appendContent(<<<HTML
<option value="$idProd" selected> $nameProduct </option>
HTML
                    );
                }else{
                    $page->appendContent(<<<HTML
<option value="$idProd" > $nameProduct </option>
HTML
                    );
                }
            }
            $page->appendContent(" </div>");

            $page->appendContent(<<<HTML
                
                <div class="grid-t2 m-0">
                    <p>Description / Remarque :</p>
                    <textarea class="textarea" name="desc1" placeholder="Entrez une description"></textarea>
                </div>
                <span class="grid-t2"> </span>
                <div>
                    <span>Capteur de début :</span>
                    <input $read type="text" onkeyup="ajust()" onchange="ajust()" placeholder="Numéro de capteur" value="$capDeb" required id="capdeb1" maxlength="2" minlength="2" name="capDeb1">
                </div>
                
                <div>
                    <span>Capteur de fin :</span>
                    <input $read type="text" onkeyup="ajust()" onchange="ajust()" placeholder="Numéro de capteur" value="$capFin" required id="capfin1" maxlength="2" minlength="2" name="capFin1">
                </div>
                <div>
                    <span>Numéro de lot :</span>
                    <input type="text" placeholder="Numéro de lot" maxlength="8" minlength="8"  value="$numLot" required name="numLot1">
                </div>
                <div>
                    <span>Numéro de série de départ :</span>
                    <input type="text" placeholder="Numéro de série" value="$SNDeb" required name="SN1" id="SN1" maxlength="8" minlength="8">
                </div>
            </div>

            <div id="addForm">


HTML
            );

        } else {
            $page->appendContent(<<<HTML
                <p class="grid-t2 Groupe">Groupe n°$ret</p>     
                <span></span>
                     <div class="form-grid">
                     <div class="grid-t2 w-100">
                     <span>Type de produit :</span>
                     
                     <select form="form-create" name="type$ret">
HTML
            );

            foreach($listeProd AS $product) {
                $nameProduct = $product["NAME"];
                $idProd = $product["ID"];
                if($nameProduct == $nameProduit ) {
                    $page->appendContent(<<<HTML
<option value="$idProd" selected> $nameProduct </option>
HTML
                    );
                }else{
                    $page->appendContent(<<<HTML
<option value="$idProd" > $nameProduct </option>
HTML
                    );
                }
            }
            $page->appendContent(" </div>");

            $page->appendContent(<<<HTML
           
                     <div class="grid-t2 m-0"><span>Description / Remarque :</span>
                     <textarea class="textarea" name="desc$ret" placeholder="Entrez une description"></textarea>
                     </div>
                     <span class="grid-t2"></span><div><span>Capteur de début :</span>
                     <input type="text" placeholder="Numéro de capteur" onkeyup="ajust()" onchange="ajust()" value="$capDeb" name="capDeb$ret" required id="capdeb$ret">
                     </div><div><span>Capteur de fin :</span>
                     <input type="text" placeholder="Numéro de capteur" value="$capFin" onkeyup="ajust()" name="capFin$ret" onchange="ajust()" required id="capfin$ret"></div>
                     <div><span>Numéro de lot :</span>
                     <input type="text" placeholder="Numéro de lot"  maxlength="8" minlength="8" value="$numLot" name="numLot$ret" required></div>
                     <div><span>Numéro de série de départ :</span><input type="text" placeholder="Numéro de série" name="SN$ret" id="SN$ret" value="$SNDeb" maxlength="8" minlength="8"required ></div>
                     </div>

HTML
            );
        }
    }
    $page->appendContent(<<<HTML
            </div>
            <div class="btn-create">
                <a class="btn-form cpointer" id="add-btn">Ajouter un lot</a>
                <a class="btn-form cpointer 
HTML
    );
    if ($nbLot == 1) $page->appendContent('dnone');
    $page->appendContent(<<<HTML
                " id="rm-btn">Supprimer un lot</a>
            </div>
            <div class="btn-create">
                <input class="cpointer" type="submit" value="Sauvegarder">
            </div>
        </form>
        <div class="container-view">
            <div class="view">
                <h3>Enceinte climatique</h3>
                <div class="enceinte">
HTML
    );

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
            <p class="led" id = "led$positionAct" >$j1</p>
        </div >
HTML
            );
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
            <p class="led" id = "led$positionAct" >$j1</p>
        </div >
HTML
            );
        }
        $page->appendContent('</div>');
    }
    if ($nbLot >= 1) {
        $valCapDeb1 = $essai->getFirstCap($listLot[0]->getId());
        $valCapFin1 = $essai->getLastCap($listLot[0]->getId());
        for ($i = 1; $i < $nbLot; $i++) {
            $valCapDeb1 .= "," . $essai->getFirstCap($listLot[$i]->getId());
            $valCapFin1 .= "," . $essai->getLastCap($listLot[$i]->getId());
        }
    } else {
        $valCapDeb1 = 0;
        $valCapFin1 = 60;
    }
    $numRet = 1;
    if ($nbLot != 0) $numRet = $nbLot;

    $page->appendContent(<<<HTML
                </div>
            </div>
        </div>
    </div>
</div>
HTML
    );

    if ($user->getRole() == 'Administrateur') {

        $page->appendContent(<<<HTML
<div class="modaldel-bg dnone">
    <div class="modaldel">
        <p id="modal-p">Etes vous sur de vouloir supprimer cet essai ?</p>
        <div id="modal-div">
            <a href="php/RemoveToDBEssai.php?id=$idEssai">Oui</a>
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
HTML
        );
    }

    if ($ret == 0) {
        $ret = 1;
    }

    $page->appendContent(<<<HTML
<script>
    const list = [
        'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7',
        'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8',
        'C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7',
        'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8',
        'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7',
        'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8',
        'G1', 'G2', 'G3', 'G4', 'G5', 'G6', 'G7',
        'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'H7', 'H8'
        ];
    let ret = $numRet;
    var addBtn = document.getElementById('add-btn');
    var form = document.getElementById('addForm');
    var btnrm = document.getElementById('rm-btn');
    var listdeb = [$valCapDeb1];
    var listfin = [$valCapFin1];
    var listHS = [];
    var listCapHS = [];
    
    ajust()

    function ajust() {
        listHS = [];
        listCapHS = [];
        var strCapHs = document.getElementById("capHS").value;
        var strCapHs = strCapHs.replace(/\s+/g, '');
        var listHS = strCapHs.split(",");
        
        listHS.forEach(function(item){
            listCapHS.push(list.indexOf(item.toString().toUpperCase())+1);
        });
        
        console.log(listCapHS);
        
        for (var i = 1; i <= ret; i++) {
            listdeb[ret - 1] = list.indexOf(document.getElementById('capdeb' + ret.toString()).value.toUpperCase())+1;
            listfin[ret - 1] = list.indexOf(document.getElementById('capfin' + ret.toString()).value.toUpperCase())+1;
        }

        document.getElementById('capfin' + ret.toString()).setAttribute("min", listdeb[ret - 1].toString() );

        for (var j = 1; j <= 60; j++) {
            document.getElementById('led' + j.toString()).classList.remove('on');
            document.getElementById('led' + j.toString()).classList.remove('HS');
        }

        for (var k = 0; k < ret; k++) {
            var retdeb = listdeb[k];
            var retfin = listfin[k];

            var nb = Number(retfin)-Number(retdeb);

            for (var l = 0; l <= nb; l++) {
                var nbid = Number(retdeb)+l;
                document.getElementById('led' + nbid.toString()).classList.add('on');
            }
        }
        
        listCapHS.forEach(function(item){
            console.log('led' + item.toString());
            if (item != "") {
                document.getElementById('led' + item.toString()).classList.add('HS')
                document.getElementById('led' + item.toString()).classList.remove('on')
            }
        });
        
    }


    addBtn.addEventListener('click', function () {
        var capfin = list.indexOf(document.getElementById('capfin' + ret.toString()).value.toUpperCase())+1;
        var capdeb = list.indexOf(document.getElementById('capdeb' + ret.toString()).value.toUpperCase())+1;
        var inputcapfin = document.getElementById('capfin' + ret.toString());
        var inputcapdeb = document.getElementById('capdeb' + ret.toString());
        var capfinP1 = parseInt(capfin) + 1;
        let strCapFin = list[capfin];
        let valListFin = 0;
        if (ret > 1) {
            valListFin = listfin[Number(ret)-2];
        }


        if (ret <= 60 && capfin < 60) {
            if (capfin) {
                if (capfin >= capdeb) {
                    if (capdeb > valListFin) {

                        listdeb.push(capfinP1);
                        listfin.push(60);

                        inputcapfin.readOnly = true;
                        inputcapdeb.readOnly = true;
                        ret++;
                        var div = document.createElement('div');
                        div.id = "child" + ret.toString();
                        div.innerHTML = '<p class="grid-t2 Groupe">Groupe n°' + ret + '</p><span class="cred"></span><span></span><div class="grid-t2 w-100"><span>Type de produit :</span><select class="input" type="text" name="type' + ret + '">$option</select></div><div class="grid-t2 m-0"><span>Description / Remarque :</span><textarea placeholder="Entrez une description" class="textarea" name="desc' + ret + '"></textarea><div class="form-grid"><div><span>Capteur de début :</span><input type="text" placeholder="Numéro de capteur" onkeyup="ajust()" onchange="ajust()" value="' + strCapFin + '"  required id="capdeb' + ret + '" name="capDeb' + ret + '"></div><div><span>Capteur de fin :</span><input type="text" placeholder="Numéro de capteur" onkeyup="ajust()" onchange="ajust()" required id="capfin' + ret + '" name="capFin' + ret + '"></div><div><span>Numéro de lot :</span><input type="text" placeholder="Numéro de lot"  maxlength="8" minlength="8"  required name="numLot' + ret + '"></div><div><span>Numéro de série de départ :</span><input type="text" placeholder="Numéro de série" required name="SN' + ret + '" id="SN' + ret + '" maxlength="8" minlength="8"></div></div>';
                        form.appendChild(div);

                        btnrm.classList.remove('dnone');
                        ajust();
                    }
                }
            }
        }
        if (ret == 60) {
            addBtn.classList.add('dnone');
        }
    })

    btnrm.addEventListener('click', function () {
        if (ret > 1) {
            ret--;
            listdeb.pop();
            listfin.pop();
            ajust();
            var inputcapfin = document.getElementById('capfin' + ret.toString());
            var inputcapdeb = document.getElementById('capdeb' + ret.toString());
            inputcapfin.readOnly = false;
            inputcapdeb.readOnly = false;
            document.getElementById("child" + (ret+1).toString() ).innerHTML = "";
            form.removeChild(form.lastChild);
            addBtn.classList.remove('dnone');
            if (ret == 1) {
                btnrm.classList.add('dnone');
            }
        }
        if(ret == 1) {
            form.innerHTML = "" ;
        }
    })
    
    
    
    
    
    

</script>


HTML
    );
}
echo $page->toHTML();