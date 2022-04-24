<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('php/autoload.php');

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$page = new WebPage("EPL - Gestion des produits");
$page->addAuthor('De Buyser Rémi');
$page->addAuthor('Nicolas Adrien');


$page->appendContent(<<<HTML
<div class="container">
        <h1 class="main-h1">Liste des produits pour etalonnage</h1>
HTML
);


$page->appendContent(<<<HTML
        <div class="grid-product-container">
            <p class="bold">Nom du produit</p>
            <p class="bold"> Seuil de détection</p>
            <p class="bold"> Seuil de détection produit posé</p>
            <p></p>
            <p></p>
HTML
);

$produit = Lot::getAllProduits();

$ret = 1;
foreach ($produit as $prod) {
    $nameproduit = $prod['NAME'];
    $idproduit = $prod['ID'];
    $seuil = $prod['SEUIL'];
    $seuilPP = $prod['SEUILPP'];
    $page->appendContent(<<<HTML
    <div class="item-grid-product">
        <input type="text" placeholder="Nom du produit" required name="nomProduit$ret" id = "nomProduit$ret" value="$nameproduit" class="input inputProduct" readonly > 
    </div>
   
    <div class="item-grid-product">
        <input type="text" placeholder="Valeur du seuil" required name="Seuil$ret" id = "Seuil$ret" value="$seuil" class="input inputProduct" readonly > 
    </div>
    
      <div class="item-grid-product">
        <input type="text" placeholder="Valeur du seuil produit posé" required name="SeuilPP$ret" id ="SeuilPP$ret" value="$seuilPP" class="input inputProduct" readonly > 
      </div>
   
    <div class="item-grid-product flex-center" id="divinput$ret"> 
        <a class="cpointer bold" id="$ret">Modifier</a>  
        <a class="cpointer dnone bold cgreen" id="val$ret">Valider</a>
    </div>
    <a class="cpointer t-end" id="modal-btn$ret">Supprimer ce produit </a>

  HTML
    );


    $page->appendContent(<<<HTML
    <script type="module">
             
                import {AjaxRequest} from  './js/AjaxRequest.js';
             
                
                var btn$ret = document.getElementById('$ret');
                var val$ret = document.getElementById('val$ret');
                var input$ret = document.getElementById('nomProduit$ret');
                var Seuil$ret = document.getElementById('Seuil$ret');
                var SeuilPP$ret = document.getElementById('SeuilPP$ret');
                var divInput$ret = document.getElementById('divinput$ret');
                
                 btn$ret.addEventListener('click', function () {
                    input$ret.classList.remove('inputProduct');
                    Seuil$ret.classList.remove('inputProduct')
                    SeuilPP$ret.classList.remove('inputProduct')
                    btn$ret.classList.add('dnone');
                    val$ret.classList.remove('dnone');
                     input$ret.readOnly=false;
                     Seuil$ret.readOnly=false;
                     SeuilPP$ret.readOnly=false;
                 });
                 
                 val$ret.addEventListener('click', function () {
                    input$ret.classList.add('inputProduct');
                    Seuil$ret.classList.add('inputProduct');
                    SeuilPP$ret.classList.add('inputProduct');
                     input$ret.readOnly=true;
                     Seuil$ret.readOnly=true;
                     SeuilPP$ret.readOnly=true;
                     btn$ret.classList.remove('dnone');
                    val$ret.classList.add('dnone');
                    ajaxUpdateProduct();
                 });
  
  
                 
                 
  function ajaxUpdateProduct() {
    new AjaxRequest({
  url: "php/setProduct.php",
        method: "post",
        parameters: {
            productName: document.getElementById("nomProduit$ret").value,
            Seuil : document.getElementById("Seuil$ret").value,
            SeuilPP : document.getElementById("SeuilPP$ret").value,
            id: $idproduit
        },
        onSuccess: function () {
        console.log('ok');
           
        }, onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    });
}
   

    </script>       
    
    <div id= "modaldel-bg$ret" class="modaldel-bg dnone">
        <div class="modaldel" id="modaldel$ret">
            <p id="modal-p$ret">Etes vous sur de vouloir supprimer ce produit ?</p>
            <div id="modal-div$ret">
                <a href="php/DeleteProduct.php?id=$idproduit">Oui</a>
                <a class="cpointer" id="modal-close$ret">Non</a>
            </div>
        </div>
    </div>
    
    <script>
        var modalClose$ret = document.getElementById('modal-close$ret');
        var modalBtn$ret = document.getElementById('modal-btn$ret');
        var modalBg$ret = document.getElementById('modaldel-bg$ret');

        modalBtn$ret.addEventListener('click', function () {
            modalBg$ret.classList.remove('dnone');
        })

        modalClose$ret.addEventListener('click', function () {
            modalBg$ret.classList.add('dnone');


        })

        var modal$ret = document.querySelector('.modaldel$ret');
        var modalp$ret = document.getElementById('modal-p$ret');
        var modaldiv$ret = document.getElementById('modal-div$ret');

        modalBg$ret.onclick = function(e) {
            if(e.target !== modal$ret && e.target !== modaldiv$ret && e.target !== modalp$ret) {
                modalBg$ret.classList.add('dnone');
            }
        }
        
        
    </script>




HTML
    );


    $ret += 1;
}

$page->appendContent(<<<HTML
        
    </div>
   <div class="btn-create item-center">
            <input id="AddProduct" class="cpointer" type="submit" value="Ajouter un nouveau Produit">
        </div>
            <div class="dnone" id="divCreate">
         <div class="grid-product-container">
           <input type="text" placeholder="Nom du produit" required name="ProdCreate" id ="ProdCreate" class="input" >
             <input type="number" placeholder="Valeur du seuil" required name="SeuilCreate" id ="SeuilCreate" class="input" min="0" max="1023" >
             <input type="number" placeholder="Valeur du seuil produit posé" required name="SeuilCreatePP" id ="SeuilCreatePP" class="input" min="0" max="1023" >
            <a class="cpointer bold" id="create">Créer</a>
             </div>
           </div>
    </div>

    <script type="module">
             
                import {AjaxRequest} from  './js/AjaxRequest.js';

                 var btncreate = document.getElementById('AddProduct');               
                var btncreate2 = document.getElementById('create')     
                var divcreate = document.getElementById('divCreate');
                
                

    btncreate.addEventListener('click', function () {
                    if(divcreate.classList.contains('dnone')){
                        divcreate.classList.remove('dnone');
                    } else {
                        divcreate.classList.add('dnone');
                    }
                });

  
  
    btncreate2.addEventListener('click', function () {
                   
                    if(divcreate.classList.contains('dnone')){
                        divcreate.classList.remove('dnone');
                    } else {
                        divcreate.classList.add('dnone');
                    }
                    ajaxAddProduct();
                 });

    
  function ajaxAddProduct() {
    new AjaxRequest({
  url: "php/AddProduct.php",
        method: "post",
        parameters: {
            productName2: document.getElementById("ProdCreate").value,
            SeuilName : document.getElementById("SeuilCreate").value,
            SeuilNamePP : document.getElementById("SeuilCreatePP").value
        },
        onSuccess: function () {
        window.location.href=window.location.href;
           
        }, onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    });
    }
    
    
    
  

              
  </script>
  
  HTML
);

echo $page->toHTML();

