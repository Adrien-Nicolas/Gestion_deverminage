<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once ('autoload.php');


$id = $_POST['id'];
$valid = $_POST['valid'];
$refresh = $_POST['refresh'];
$minVal = 0;
$maxVal = 0;
$temp = 0;
$minVal = Valeur::getMinValFromIdCap($id);
$maxVal = Valeur::getMaxValFromIdCap($id);

$authentication = new SecureUserAuthentication();
$user = $authentication->getUserFromSession();
$temp = Temperature::getlastTemp();
if($valid == 0){
    $cap = Capteur::getCapteurFromId($id);


    $html = <<<HTML
    <div class="holds-the-iframe"><iframe title="Graphique de la luminosité par rapport au temps" src="graph.php?id=$id&refresh=$refresh" width="100%" height="600px"></iframe></div>
        <div class="tube-info">
            <div>
HTML;

        $html .= <<<HTML
              <span>Valeur minimum : <span class="bold"> $minVal</span> </span>
               <p></p>
              <span> Valeur maximum : <span class="bold"> $maxVal</span></span> 
                <p></p>
              <span> Dernière temperature : <span class="bold"> $temp</span></span>
HTML;
$html .= <<<HTML
    
            </div>
            <div class="iframerow">
 HTML;
    if ($user->getRole() == 'Administrateur') {
        $html .= <<<HTML
                <p></p>
                <p></p>
                <a href="php/ConformTube.php?id=$id" class="cpointer conforme">Cliquez ici pour conformer ce tube
HTML;
                   }
$html .= <<<HTML
                </a>
            </div>
        </div>
HTML;
}

else {
$html = <<<HTML
        <div class="holds-the-iframe"><iframe class="holds-the-iframe" title="Graphique de la luminosité par rapport au temps" src="graph.php?id=$id&refresh=$refresh" width="100%" height="600px" ></iframe></div>
        <div class="tube-info">
            <div>
HTML;

    $html .= <<<HTML
              <span>Valeur minimum : <span class="bold"> $minVal</span> </span>
               <p></p>
              <span> Valeur maximum : <span class="bold"> $maxVal</span></span> 
                <p></p>
              <span> Dernière temperature : <span class="bold"> $temp</span></span>
HTML;
    $html .= <<<HTML
            </div>
            <div class="iframerow">
 HTML;
              if ($user->getRole() == 'Administrateur') {
$html .= <<<HTML
                <p></p>
                <p></p>
                <a href="php/NotConformTube.php?id=$id" class="cpointer Nonconforme" >Cliquez ici pour déconformer ce tube   
HTML;
              }
$html .= <<<HTML
                </a>
            </div>
        </div>
HTML;
}
echo $html;
