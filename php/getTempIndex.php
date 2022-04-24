<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once ('autoload.php');


$authentication = new SecureUserAuthentication();
$user = $authentication->getUserFromSession();

$temp = Temperature::getlastTemp();

$html = <<<HTML

<div class="grid-temp">

<h1> Temperature de l'enceinte : </h1>  
        <p>$temp</p>
</div>

HTML;
echo $html;
