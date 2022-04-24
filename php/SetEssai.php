<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('autoload.php');


$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
    header('location: ./login.php');
    exit();
}

$user = $authentication->getUserFromSession();
$listDesc = [];

$idEssai = (int)$_GET["id"];
$essai = Essai::getEssaiFromId($idEssai);



if ($user->getRole() == 'Administrateur' || $essai->getConfig() == 0 || $essai->getNbVal()==0) {

    $list = [];
    $continue = true;
    $ret = 1;

    //liste des emplacements
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
    $retFin = 0;
    $valid = true;





    while ($continue && $valid) {

        if (isset($_POST["capDeb" . $ret])) {
            $debTemp = array_search(strtoupper($_POST["capDeb" . $ret]), $emplacement) + 1;
            $finTemp = array_search(strtoupper($_POST["capFin" . $ret]), $emplacement) + 1;
            if ($debTemp <= 0 || $debTemp < $retFin || $finTemp > 60) {
                $valid = false;

            }

            (int)$SN2 = substr($_POST["SN" . $ret], -4);
            (int)$SN1 = substr($_POST["SN" . $ret], 0, -4);

            if ($SN1 < $SN2 && $SN2 == 0){
                header("Location: ../essai.php?idessai=$idEssai&error=Le numéro de Serie est incorrect");
                exit();

            }

            if(!is_numeric($_POST["SN" . $ret])) {
                header("Location: ../essai.php?idessai=$idEssai&error=Le numéro de Serie est incorrect");
                exit();
            }
            if(!is_numeric($_POST["numLot" . $ret])){
                header("Location: ../essai.php?idessai=$idEssai&error=Le numéro de Lot est incorrect");
                exit();
            }



            $retFin = $finTemp;
            $listTemp = [
                "capDeb" => $_POST["capDeb" . $ret],
                "capFin" => $_POST["capFin" . $ret],
                "numLot" => $_POST["numLot" . $ret],
                "SN" => $_POST["SN" . $ret],
                "desc" => $_POST["desc" . $ret],
                "type" => $_POST["type" . $ret]
            ];
            array_push($list, $listTemp);
        } else {
            $continue = false;
        }


        $ret++;
    }


    if ($valid && isset($_POST["CapHS"])) {

        //recuperer id lot en fonction idEssai
        $idLot = Lot::GetidLotEssai($idEssai);
        $strCapHS = $_POST["CapHS"];
        $listCapHS = [];
        $strCapHS = str_replace(' ', '', $strCapHS);
        $listTempCapHS = preg_split("/\,/", $strCapHS);

        foreach ($listTempCapHS AS $cap) {
            $cap = strtoupper($cap);
            if(in_array($cap, $emplacement)) {
                echo "trouvé ! ";
                array_push($listCapHS, array_search($cap, $emplacement) + 1);
            }
        }
        var_dump($strCapHS);
        var_dump($listCapHS);

        // update les capteurs et met idLot et SN à 0
        foreach ($list as $value) {
            Capteur::UpdateCapSet0($idEssai);
        }

        // Puis supprimer tout les lots et ducoup dans la table posseder idEssai = idEssai
        Lot::RemoveFromPosseder($idEssai);

        for ($i = 0; $i < sizeof($idLot); $i++) {
            //verifier si il existe encore des id lot que tu viens de recup dans la table posseder
            $countidLot = Lot::VerifIdLot($idLot[$i]);

            //si il y en a plus alors tu supprimes le lot
            if ($countidLot == 0) {
                $lot = Lot::getLotFromID($idLot[$i]);
                $listDesc += [$lot->getNumLot() => $lot->getDescription()];
                Lot::removeToDBLot($idLot[$i]);
            }
        }

        // sinon tu laisses

        foreach ($list as $value) {
            $deb = array_search(strtoupper($value["capDeb"]), $emplacement) + 1;
            $fin = array_search(strtoupper($value["capFin"]), $emplacement) + 1;
            $SN = $value["SN"];
            $numLot = $value["numLot"];
            $valid = 1;
            $description = $value["desc"];
            $product = $value["type"];
            //creer lot si il existe pas




            echo "deb :";

            $ess = Essai::getAllSN();


            if (isset($listDesc["$numLot"])) {
                $description .= $listDesc["$numLot"];
            }

            /*
            $idProd = Lot::getIdProduitByName($product);
            */

            Lot::createLot($numLot, $valid, (int)$product, $description);


            // si il existe alors tu ajoute dans posseder
            Lot::insertToPosseder($numLot, $idEssai, $deb, $fin);


            $retSN = 0;
            var_dump($listCapHS);
            for ($i = $deb; $i <= $fin; $i++) { //$i -> emplacement
                // UPDATE Capteur(idLot, SN) VALUES(idlot, $SN+$retSN) WHERE idEssai = $id AND position = $i
                if (!in_array($i, $listCapHS)){
                    $SNTEMP = $SN;
                    $SN2temp = (int)substr($SNTEMP, -4) + $retSN;

                    $SN2 = (string) $SN2temp;
                    for($j = 0; $j < 4-strlen($SN2temp); $j++){
                        $SN2 = "0".$SN2;
                    }

                    $SN1 = substr($SN, 0, -4);

                    $SNfinal = (string)$SN1 . (string)$SN2;
                    echo "gnagna : ".$SNfinal;

                    Capteur::UpdateCap($numLot, $SNfinal , $idEssai, $i);
                    $retSN += 1;
                } else {
                    echo $idEssai."   ". $i;
                    Capteur::UpdateCapSetHS($idEssai, $i);

                }
            }

            $retlot = Lot::getLotFromNum($numLot);
            if ($retlot->getNbCap() > 0) {
                $retlot->updateValid();
            }
        }
        if ($essai->getIdOp() == 0) {
            $idOp = $user->getId();
        } elseif ($user->getRole() == "Administrateur" && isset($_POST["idOp"])) {
            $idOp = $_POST["idOp"];
        } else {
            $idOp = $essai->getIdOp();
        }

        $essai->updateEssai(1, $idOp);

    }

    header("Location: ../index.php");
    exit();

} else {

    header("Location: ./?=Cet Essai à déja été modifié");
    exit();
}
