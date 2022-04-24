<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
set_time_limit(20);

require_once('php/autoload.php');
require_once ('php/src/Temperature.php');

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$conf = ConfigGraph::getConfigFromId(1);



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






/*
if(isset($_POST['OrdonnéesVerte'])){
    $seuilBas = (int)$_POST['OrdonnéesVerte'];
}else{
    $seuilBas = 600;
}

if(isset($_POST['OrdonnéesRouge'])){
    $seuilHaut = (int)$_POST['OrdonnéesRouge'];
}else{
    $seuilHaut = 900;
}

if(isset($_POST['BlueTraceName'])){
    $NameBlue = $_POST['BlueTraceName'];
}else{
    $NameBlue = 'Intensité lumineuse';
}


if(isset($_POST['RedTraceName'])){
    $NameRed = $_POST['RedTraceName'];
}
if(isset($_POST['OrangeTraceName'])){
    $NameOrange =$_POST['OrangeTraceName'];
}
if(isset($_POST['GreenTraceName'])){
    $NameGreen  = $_POST['GreenTraceName'];
}
*/
$id=$_GET['id'];

$refresh = (int)$_GET["refresh"];

$capteur = Capteur::getCapteurFromId($id);
$idCap = $capteur->getId();
$SN = $capteur->getSN();
$valeur = $capteur->getValues();



$sql = "SELECT time FROM Essai WHERE id = (SELECT idEssai FROM Capteur WHERE id = $idCap)";
$result = MyPDO::getInstance()->prepare($sql);
$result->execute();
$timestamp = $result->fetch();
$date = $timestamp["time"];

$conn = "SELECT idEssai FROM Capteur WHERE id = $idCap";
$result2 = MyPDO::getInstance()->prepare($conn);
$result2->execute();
$idessai = $result2->fetch();
$essai = $idessai["idEssai"];


$temperature = Temperature::getTempidEssai($essai);
$id = $temperature->getId();
$values = $temperature->getValues();

$essaii = Essai::getEssaiFromId($essai);
$timestamp = $essaii->getDate();

$timeCap =$capteur->getLastdate();
$product =$capteur->getProduct();


$min = 300000;
$date = time();

$addHead = "";

if($timestamp + $timeCap > ($date*1000) - $min && $refresh== 0 ){
    $addHead = '<meta http-equiv="refresh" content="10">';
}


$html = <<<HTML

<!doctype html>
<html>
	<head>
            $addHead
		<meta charset="utf-8">
  		<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
		<script src="https://cdn.plot.ly/plotly-locale-fr-latest.js"></script>
	</head>
	<body style="overflow-y: hidden;">
  		<div id="graph"></div>
		<script>
			var selectorOptions = {
    buttons: [{
        step: 'hour',
        stepmode: 'backward',
        count: 1,
        label: '1h'
    }, {
        step: 'hour',
        stepmode: 'backward',
        count: 5,
        label: '5h'
    }, {
        step: 'hour',
        stepmode: 'todate',
        count: 10,
        label: '10h'
    }, {
        step: 'all',
        label: 'tout'
    }],
};
    
	var trace1 = prepData();
    var trace2 = prepTemp();
    var trace3 = prepSeuil();
    var trace4 = prepSeuil2();
    var data = [trace1, trace2, trace3, trace4];
    
    var layout = {
        title: '$product n°$SN',
        width : 970,
        height : 600,
        
        xaxis: {
            rangeselector: selectorOptions,
            rangeslider: {},
            title: '$abscisse',
            titlefont: {
                color: 'black',
                size: 12
            },
            rangemode: 'tozero'
        },

        yaxis: {
            fixedrange: true,
            title: '$nomOrdonneeGauche',
			tickfont: {color: 'rgb0,0,255'},
            titlefont: {
                color: 'black',
                size: 12
            },
     
       },
        
        yaxis2: {
             fixedrange: true,
			title: '$nomOrdonneeDroite',
			titlefont: {
				color: 'black',
				size: 12
			},
			tickfont: {color: 'rgb(183, 11, 0)'},
			overlaying: 'y',
			side: 'right'
		}

    };

	var config = {locale: 'fr'};

	Plotly.newPlot('graph', data, layout, config);
	
function prepData() {
    var x = [];
    var y = [];
HTML;
foreach ($valeur as $ret) {
    $time = $timestamp + $ret->getTime();
    $value = $ret->getValue();
    $html .= <<<HTML
x.push(new Date( $time ));
y.push($value);
HTML;
}

$html .= <<<HTML

    return {
        name: '$nomBleu',
        mode: 'lines',
        xaxis: 'x', 
        x: x,
        y: y
    };
}


function prepSeuil2(){
var x = [];
    var y = [];
HTML;
foreach ($valeur as $ret) {
    $time = $timestamp;
    $time2 = $timestamp + $timeCap;
    $html .= <<<HTML
    x.push(new Date( $time ));
    y.push($seuilBas);
    x.push(new Date( $time2 ));
    y.push($seuilBas);
HTML;

}
$html .= <<<HTML
    return {
        name: '$nomVert',
        mode: 'lines',
        xaxis: 'x', 
        x: x,
        y: y
    };
}


function prepSeuil(){
var x = [];
    var y = [];
HTML;
foreach ($valeur as $ret) {
    $time = $timestamp;
    $time2 = $timestamp + $timeCap;

    $html .= <<<HTML
    x.push(new Date( $time ));
    y.push($seuilHaut);   
    x.push(new Date( $time2 ));
    y.push($seuilHaut);
HTML;

}
$html .= <<<HTML
    return {
        name: '$nomRouge',
        mode: 'lines',
        xaxis: 'x', 
        x: x,
        y: y
    };
}


function prepTemp() {
    var x = [];
    var y = [];
    
HTML;
    foreach ($values as $ret) {
        $time = $timestamp + $ret->getTime();
        $value = $ret->getValue();
        $html .= <<<HTML
x.push(new Date( $time ));
y.push($value);
HTML;
    }

    $html .= <<<HTML
    return {
        name: '$nomOrange',
        yaxis: 'y2',
        xaxis: 'x', 
        mode: 'lines',
        x: x,
        y: y
    };
}
		</script>
	</body>
</html>

HTML;

    echo $html;
