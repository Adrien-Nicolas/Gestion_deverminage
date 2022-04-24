<?php

require_once('autoload.php');

$html = "";

$lot = Lot::getLotFromID($_GET["id"]);
$product = $lot->getProduct();
$idLot = $_GET["id"];

$nbCap = $lot->getNbCap();
$nbPage = round($nbCap/60);
if (($nbCap%60 != 0 && $nbCap/60 > 1) || $nbPage==0 ) {
    $nbPage+=1;
}

$date = date("j/m/Y à H:i:s", $lot->getFirstDateEssai()/1000);
$dateNow = date("j/m/Y à H:i:s");

$numLot = $lot->getNumLot();
$numLot0 = $numLot[0];
$numLot1 = $numLot[1];
$numLot2 = $numLot[2];
$numLot3 = $numLot[3];
$numLot4 = $numLot[4];
$numLot5 = $numLot[5];
$numLot6 = $numLot[6];
$numLot7 = $numLot[7];

$quantitatif = (int)"$numLot[4]$numLot[5]$numLot[6]$numLot[7]";

$debTemp1 = 1;
$debTemp2 = $debTemp1 + 20;
$debTemp3 = $debTemp2 + 20;

$listCap = $lot->getAllCapteurs();

$NQA = $lot->getNQA();

$idEssai = Essai::getEssaiFromLot($idLot);
$idOp = Utilisateur::getUserByidEssai($idEssai);
$User = Utilisateur::getUtilisateurFromId($idOp);
$ln = $User->getLastname();
$fn = $User->getFirstname();



for ($index=1;$index<=$nbPage;$index++) {

    $debTemp1 = 1 +($index-1)*60;
    $debTemp2 = $debTemp1 + 20;
    $debTemp3 = $debTemp2 + 20;

    $html .= <<<HTML
<table cellspacing="0" style="border-collapse:collapse; width:1000px">
    <tbody>
    <tr>
        <td colspan="5" rowspan="4" style="height:90px; width:133px" class="bg">
            <img src="http://dev.epl.local/img/logo.png" style="width: 133px" />
        </td>
        <td colspan="24" rowspan="3" class="border" style="height:70px; width:576px">
            <span style="font-size:35px"><strong>Essai De D&eacute;verminage</strong></span>
        </td>
        <td colspan="5" class="border" style="height:30px; width:115px">
            <span style="font-size:13px">R3-EDD</span>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="border" style="height:20px; width:115px">
            <span style="font-size:13px"> R&eacute;vision 4</span>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="border" style="height:20px; width:115px">
            <span style="font-size:13px">Page $index sur $nbPage</span>
        </td>
    </tr>
    <tr>
        <td colspan="21" class="border" style="height:20px; width:507px;">
            <span style="font-size:13px">Approbateur : C.AMBROISE</span>
        </td>
        <td colspan="8" class="border" style="height:20px; width:184px">
            <span style="font-size:12px">Date de mise &agrave; jour : 05/01/2021</span>
        </td>
    </tr>
    <tr>
        <td class="nw" colspan="34"
            style="height:20px; width:824px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td class="nw" colspan="5" style="height:20px; width:133px"><span
                style="font-size:15px">Nombre de cycle :</span>
        </td>
        <td colspan="2" class="border" style="height:20px; width:52px">
            <span style="font-size:15px">6</span>
        </td>
        <td colspan="4" style="height:20px; width:92px">
            <span style="font-size:15px">Dur&eacute;e total :</span>
        </td>
        <td colspan="5" class="border" style="height:20px; width:133px">
            <span style="font-size:15px">19h30</span>
        </td>
        <td colspan="3" style="height:20px; width:69px">
            <span style="font-size:15px">Logiciel :</span>
        </td>
        <td colspan="3" class="border" style="height:20px; width:69px">
            <span style="font-size:15px">Kratos</span>
        </td>
        <td colspan="7" style="height:20px; width:161px">
            <span style="font-size:15px">Nom du programme :</span>
        </td>
        <td colspan="5" class="border" style="height:20px; width:115px">
            <span style="font-size:15px">PRG200921A1</span>
        </td>
    </tr>
    <tr>
        <td colspan="34" style="height:20px; width:824px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td class="border" style="background-color:#d0cece; height:20px;">
            <span style="font-size:13px">&Eacute;tape</span>
        </td>
        <td colspan="15" class="border" style="background-color:#d0cece; height:20px;width:369px">
            <span style="font-size:13px"><strong>Mesure du cycle &agrave; r&eacute;alis&eacute; - Dur&eacute;e (3h15)</strong></span>
        </td>
        <td colspan="18" rowspan="15" class="border courbe" style=" height:300px; width:414px">
        <img src="http://dev.epl.local/img/CourbePDF.png" width="500px">
        </td>
    </tr>
    <tr>
        <td rowspan="2" class="border" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">1</span>
        </td>
        <td colspan="15" rowspan="2" class="border" style="height:40px; width:369px">
            <span style="font-size:12px">Temp&eacute;rature Ambiante<br/>
			Tube &agrave; LED&nbsp;:
                <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td rowspan="2" class="border" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">2</span>
        </td>
        <td colspan="15" rowspan="2" class="border" style="height:40px; width:369px">
            <span style="font-size:12px">
                25 min jusqu&rsquo;&agrave; Temp&eacute;rature Basse : -25&deg;C<br/>Tube &agrave; LED :
                <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td rowspan="2" class="border" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">3</span>
        </td>
        <td colspan="15" class="border" rowspan="2" style="height:40px; width:369px">
            <span style="font-size:12px">25 min &agrave; Temp&eacute;rature Basse : -25&deg;C<br/>Tube &agrave; LED :
                <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td rowspan="2" class="border" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">4</span>
        </td>
        <td colspan="15" rowspan="2" class="border"
            style="height:40px; width:369px"><span style="font-size:12px">
                <span>50 min &agrave; Temp&eacute;rature Basse : -25&deg;C<br/>Tube &agrave; LED (5 Min
                    <span style="font-size:9pt; color:#00b050; font-family:Arial,sans-serif">ON </span>
                    <span style="font-size:9pt; font-family:Arial,sans-serif">+ 5 Min</span>
                    <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
                    <span style="font-size:9pt; font-family:Arial,sans-serif">) x5</span>
                </span>
        </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="border" rowspan="2" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">5</span>
        </td>
        <td colspan="15" rowspan="2" class="border" style="height:40px; width:369px">
            <span style="font-size:12px">
                30 min jusqu&rsquo;&agrave; Temp&eacute;rature Haute : +55&deg;C<br/>
			    Tube &agrave; LED :
                <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="border" rowspan="2" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">6</span>
        </td>
        <td colspan="15" rowspan="2" class="border" style=" height:40px; width:369px">
            <span
                    style="font-size:12px">15 min &agrave; Temp&eacute;rature Haute : +55&deg;C<br/>
			Tube &agrave; LED :
                <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td rowspan="2" class="border" style="background-color:#d0cece; height:40px; width:41px">
            <span style="font-size:12px">7</span>
        </td>
        <td colspan="15" rowspan="2" class="border" style=" height:40px; width:369px; font-size:12px">
                <span>50 min &agrave; Temp&eacute;rature Haute : +55&deg;C<br/>
                    Tube &agrave; LED (5 Min
                    <span style="font-size:9pt; color:#00b050; font-family:Arial,sans-serif">ON</span>
                    <span style="font-size:9pt; color:black; font-family:Arial,sans-serif"> + 5 Min </span>
                    <span style="font-size:9pt; color:red; font-family:Arial,sans-serif">OFF</span>
                    <span style="font-size:9pt; color:black; font-family:Arial,sans-serif">) x5</span>
                </span>
            </span>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td colspan="34" style="height:20px; width:824px">
            <span style="font-size:15px"><strong>Les produits doivent &ecirc;tre mis en essai de d&eacute;verminage &agrave; 16H pour &ecirc;tre r&eacute;cup&eacute;r&eacute;s le lendemain matin &agrave; 11h30</strong></span>
        
    </tr>
    <tr>
        <td colspan="34"
            style="height:15px; width:824px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="3" style="height:25px; width:87px">
            <span style="font-size:15px"><strong>Lot n&deg; :&nbsp;</strong></span>
        </td>
        <td colspan="8" style="height:25px; width:190px;"></td>
        <div class="border" style="position:absolute; top: 564px; left: 60px; padding: 3.5px 9px; background:#fff2cc">$numLot0</div>
        <div class="border" style="position:absolute; top: 564px; left: 88px; padding: 3.5px 9px; background:#fff2cc">$numLot1</div>
        <div class="border" style="position:absolute; top: 564px; left: 116px; padding: 3.5px 9px; background:#fff2cc">$numLot2</div>
        <div class="border" style="position:absolute; top: 564px; left: 144px; padding: 3.5px 9px; background:#fff2cc">$numLot3</div>
        <div class="border" style="position:absolute; top: 564px; left: 172px; padding: 3.5px 9px; background:#fff2cc">$numLot4</div>
        <div class="border" style="position:absolute; top: 564px; left: 200px; padding: 3.5px 9px; background:#fff2cc">$numLot5</div>
        <div class="border" style="position:absolute; top: 564px; left: 228px; padding: 3.5px 9px; background:#fff2cc">$numLot6</div>
        <div class="border" style="position:absolute; top: 564px; left: 256px; padding: 3.5px 9px; background:#fff2cc">$numLot7</div>
        <td colspan="7" style="height:25px; width:179px">
            &nbsp;
        </td>
    
        <td colspan="4" style="height:25px; width:92px">
            <span style="font-size:15px"><strong>Quantitatif :&nbsp;</strong></span>
        </td>
        <td colspan="8" class="border" style="background-color:#fff2cc; height:25px; width:184px">
            <span style="font-size:19px"><strong>$quantitatif</strong></span>
        </td>
        <td colspan="4" style="height:25px; width:92px">
            &nbsp;
        </td>
    </tr>
  
    <tr>
        <td colspan="30" style="height:15px; width:824px">
          
        </td>
       
    </tr>
    <tr>
        <td colspan="4" style="height:20px; text-align:right; width:110px">
            <span style="font-size:15px">Appareil :&nbsp;</span>
        </td>
        <td colspan="6" class="border" style="height:20px; width:144px">
            <span style="font-size:15px">EPL-U18</span></td>
        <td colspan="8" style="height:20px; width:202px">
    </td>
      
       
        <td colspan="5" style="height:20px; text-align:right; width:115px">
            <span style="font-size:15px">Qt&eacute; pr&eacute;lev&eacute;e :&nbsp;</span>
        </td>
        <td colspan="6" class="border" style="height:20px; width:138px">
            $nbCap
        </td>
        <td colspan="5" style="height:20px; text-align:center; width:115px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="30" style="height:15px; width:824px">
            &nbsp;&nbsp;Produit : <span class="border" style="padding: 10px">$product</span>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="height:20px; text-align:right;width:110px">
            <span style="font-size:15px">S/N :&nbsp;</span>
        </td>
       
        <td class="border" colspan="6" style="height:20px; width:144px">
            <span style="font-size:15px">TT03892</span></td>
        <td colspan="8" style="height:20px; width:202px">
            &nbsp;
        </td>
       
        <td colspan="5" style="height:20px; text-align:right; width:115px">
            <span style="font-size:15px">NQA cible :&nbsp;</span>
        </td>
        <td colspan="6" class="border" style="height:20px; width:138px">
            $NQA
        </td>
        <td colspan="5" style="height:20px;width:115px">
            &nbsp;
        </td>
    </tr>
    
    
    <tr>
        <td colspan="34"
            style="height:15px; width:824px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td class="border" style="background-color:#d0cece; height:20px;">
            <span style="font-size:14px">Item :</span></td>
        <td colspan="6" class="border" style="background-color:#d0cece; height:20px;width:144px">
            <span style="font-size:14px">N&deg; de S&eacute;rie pr&eacute;lev&eacute; :</span>
        </td>
        <td class="border" colspan="4" style="background-color:#d0cece; height:20px; width:92px">
            <span style="font-size:14px">Valide (C/NC) :</span>
        </td>
        <td class="border" style="background-color:#d0cece;">
            <span style="font-size:14px">Item :</span></td>
        <td class="border" colspan="6" style="background-color:#d0cece; height:20px; width:138px">
            <span style="font-size:14px">N&deg; de S&eacute;rie pr&eacute;lev&eacute; :</span>
        </td>
        <td class="border" colspan="4" style="background-color:#d0cece; height:20px; width:92px">
            <span style="font-size:14px">Valide (C/NC) :</span>
        </td>
        <td class="border" colspan="2" style="background-color:#d0cece; height:20px; width:46px">
            <span style="font-size:14px">Item :</span></td>
        <td class="border" colspan="6" style="background-color:#d0cece; height:20px;width:138px">
            <span style="font-size:14px">N&deg; de S&eacute;rie pr&eacute;lev&eacute; :</span>
        </td>
        <td class="border" colspan="4" style="background-color:#d0cece; height:20px; width:92px">
            <span style="font-size:14px">Valide (C/NC) :</span>
        </td>
    </tr>
    <!-- Début Grille capteurs  -->
HTML;
    for ($i = 0; $i < 20; $i++) {

        $Temp1 = $debTemp1 + $i;
        $SN1 = "";
        $valid1 = "";
        if(isset($listCap[$Temp1-1])){
            $cap = $listCap[$Temp1-1];
            $SN1 = $cap->getSN();
            $valid1 = $cap->getValidPDF();
        }

        $SN2 = "";
        $valid2 = "";
        $Temp2 = $debTemp2 + $i;
        if(isset($listCap[$Temp2-1])){
            $cap = $listCap[$Temp2-1];
            $SN2 = $cap->getSN();
            $valid2 = $cap->getValidPDF();
        }

        $SN3 = "";
        $valid3 = "";
        $Temp3 = $debTemp3 + $i;
        if(isset($listCap[$Temp3-1])){
            $cap = $listCap[$Temp3-1];
            $SN3 = $cap->getSN();
            $valid3 = $cap->getValidPDF();
        }

        $html .= <<<HTML
    <tr>
        <td class="border" style="background-color:#d0cece; height:20px;">
            <span style="font-size:14px">$Temp1</span>
        </td>
        <td colspan="6" class="border" style="background-color:#fff2cc; height:20px; width:144px">
            $SN1
        </td>
        <td colspan="4" class="border" style="background-color:#fff2cc; height:20px; width:92px">
            $valid1
        </td>
        <td class="border" style="background-color:#d0cece;">
            <span style="font-size:14px">$Temp2</span></td>
        <td colspan="6" class="border" style="background-color:#fff2cc; height:20px; width:138px">
            $SN2
        </td>
        <td colspan="4" class="border" style="background-color:#fff2cc; height:20px; width:92px">
            $valid2
        </td>
        <td colspan="2" class="border" style="background-color:#d0cece; height:20px; width:46px">
            <span style="font-size:14px">$Temp3</span></td>
        <td colspan="6" class="border" style="background-color:#fff2cc; height:20px; width:138px">
            $SN3
        </td>
        <td colspan="4" class="border" style="background-color:#fff2cc; height:20px; width:92px">
            $valid3
        </td>
    </tr>
HTML;
    }
    $html .= <<<HTML
    <!-- Fin Grille capteurs  -->
    
    <tr>
        <td colspan="34"

            style="height:15px; width:824px">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="34"
            class = "border"
            style="height:20px; width:824px">
            <span style="font-size:15px"><strong>L&#39;op&eacute;rateur certifie la conformit&eacute; des produits suite &agrave; l&#39;essai de d&eacute;verminage</strong></span>
        </td>
    </tr>
    <tr>
        <td colspan="17"" style="width:412px; padding:14px 0">
            <span>Date de début du Lot : </span>
            <span class="border" style="padding: 10px">$date</span>
        </td>
         
        <td colspan="17" style="width:412px">
            <span>Date d'impression : </span>
            <span class="border" style="padding: 10px">$dateNow</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2"
            style="height:40px; width:64px">
            &nbsp;
        </td>
        <td colspan="6"
            class = "border"
            style="height:20px; width:144px">
            <span style="font-size:13px"><span style="color:black">Op&eacute;rateur : </span></span>
       
        </td>
        
        <td colspan="5" rowspan="2"
            style="height:40px; width:133px">
           
        </td>
        <td colspan="6"
            class = "border"
            style="height:20px; width:138px">
            <span style="font-size:15px">Date de signature :</span> </td>
        <td colspan="6" rowspan="2"

            style="height:40px; width:138px">
            &nbsp;
        </td>
        <td colspan="6"
            class = "border"
            style="height:20px; width:138px">
            <span style="font-size:13px">Signature :</span>
        </td>
        <td colspan="3" rowspan="2" style="height:40px; width:69px">&nbsp;
        </td>
    </tr>
    <tr>
        <td
                class = "border"
                colspan="6" style="background-color:#fff2cc;  height:45px; width:144px">&nbsp;  $ln $fn</td>
        <td
                class = "border"
                colspan="6" style="background-color:#fff2cc;  height:45px; width:138px">&nbsp;</td>
        <td
                class = "border"
                colspan="6" style="background-color:#fff2cc;  height:45px; width:138px">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="34" style="width:824px">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="11" style= "height:60px; width:277px"><span
                style="font-size:13px"><span>R&eacute;serv&eacute; &agrave; la qualit&eacute; :</span></span>
        </td>
        <td colspan="11"
            class = "border"
            style="height:60px; width:271px">
            &nbsp;
        </td>
        <td colspan="12" style="height:60px; width:276px">&nbsp;</td>
        
    </tr>
    
    </tbody>
</table>
HTML;

}

$html.=<<<HTML
<style>
        .border{
            border:1px solid black;
        }

        body {
            font-family:Calibri,sans-serif
        }

        td {
            text-align: center;
            vertical-align: middle;
        }

        .nw {
            white-space: nowrap;
        }
        
        table {
            position: relative;
            left: -42px;
            top: -25px;
        }
        
</style>

HTML;

$filename = "Procès_Verbal_Lot_".$numLot;

// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

//var_dump($dompdf->getPaperSize());

$dompdf->getOptions()->set(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper(array(0, 0, 1145, 755), 'landscape');
// Render the HTML as PDF

$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($filename,array("Attachment"=>0));