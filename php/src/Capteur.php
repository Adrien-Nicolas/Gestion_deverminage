<?php

declare(strict_types=1);

ini_set('memory_limit', '200100M');

class Capteur
{
    private $id;
    private $idLot;
    private $SN;
    private $valid;
    private $position;
    private $idEssai;

    /**
     * Constructeur de la classe Capteur
     *
     * @param int $id
     * @param int $idLot
     * @param string $SN
     * @param int $valid
     * @param int $position
     * @param int $idEssai
     */


    public function __construct(int $id, int $valid, int $position, int $idEssai, int $idLot = null, string $SN= null)
    {
        $this->id = $id;
        $this->idLot = $idLot;
        $this->SN = $SN;
        $this->valid = $valid;
        $this->idEssai = $idEssai;
        $this->position = $position;

    }


    /**
     * Méthode de la classe Capteur qui permet la création d'un Capteur avec son ID
     *
     * @param int $id
     * @return Capteur
     */

    static function getCapteurFromId(int $id): Capteur
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * 
FROM Capteur
WHERE id = :id;
SQL
            );
        } catch (Exception $e) {
        }
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Capteur($id, (int)$result["valid"], (int)$result["position"], (int)$result["idEssai"], (int)$result["idLot"], $result["SN"]);
    }

    /**
     * Méthode de la classe Capteur qui permet la création d'un Capteur avec son numéro de serie
     *
     * @param string $SN
     * @return Capteur
     */

    static function getCapteurFromSN(string $SN): Capteur
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * 
FROM Capteur
WHERE SN = :SN;
SQL
            );
        } catch (Exception $e) {
        }
        $result->bindValue(':SN', $SN);
        $result->execute();
        $result = $result->fetch();
        return new Capteur((int)$result["id"], (int)$result["valid"], (int)$result["position"], (int)$result["idEssai"], (int)$result["idLot"], $result["SN"]);
    }




    /**
     * Méthode permetant de supprimer les Capteurs
     *
     *
     */


    static function removeToDB(int $id)
    {
        try {
            $sql = "DELETE FROM Capteur WHERE id = :id; ";
            $result = MyPDO::getInstance()->prepare($sql);
            $result->bindValue(':id', $id);
            $result->execute();
        } catch (Exception $e) {
        }

    }


    /**
     * Méthode de la classe Capteur, permet l'obtention valeurs valides
     *
     * @return array
     */
    public function getValues(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Valeur 
WHERE idCap = :id
ORDER BY time, value ASC 
LIMIT 1000000
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $val) {
            array_push($ret, new Valeur((int)$val["idCap"], (int)$val["time"], (int)$val["value"], (int)$val["valid"]));
        }
        return $ret;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention du timestamp de l'essai
     *
     * @return array
     */

    public function getTimeEssai(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT date FROM Essai
WHERE  id = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $val) {
            array_push($ret, new Valeur((int)$val["time"]));
        }
        return $ret;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention valeurs invalides
     *
     * @return array
     */

    public function getFalseValues(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Valeur
WHERE valid == 0
SQL
        );

        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $sql) {
            array_push($ret, $sql["id"]);
        }
        return $ret;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention de l'ID Lot
     *
     * @return int
     */
    public function getIdLot(): int
    {
        return $this->idLot;

    }

    /**
     * Méthode de la classe Capteur, permet l'obtention de la position
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention de l'ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention du numéro de série
     *
     * @return string
     */
    public function getSN(): string
    {
        return $this->SN;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention de Valid
     *
     * @return int
     */
    public function getValid(): int
    {
        return $this->valid;
    }

    /**
     * Méthode de la classe Capteur, permet l'obtention de Valid pour le PDF
     *
     * @return String
     */
    public function getValidPDF(): String
    {
        $ret = "C";
        if ($this->valid == 0) {
            $ret = "NC";
        }
        return $ret;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention du numéro de série
     *
     * @return int
     */
    public function getidEssai(): int
    {
        return $this->idEssai;
    }


    /**
     * Méthode de la classe Capteur, permet l'obtention de la derniere valeur du capteur
     *
     * @return int
     */

    public function getLastdate(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT time FROM Valeur
WHERE idCap = :id
ORDER BY 1 DESC LIMIT 1 
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["time"];;
    }


    /**
     * Méthode de la classe Capteur, permet l'affectation de la position
     *
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * Méthode de la classe Capteur, permet l'affectation de ID_essai
     *
     * @param int $idEssai
     */
    public function setValue(int $idEssai): void
    {
        $this->idEssai = $idEssai;
    }


    /**
     * Méthode de la classe Capteur, permet l'affectation de Valid
     *
     * @param int $valid
     */
    public function setValid(int $valid): void
    {
        $this->valid = $valid;
    }


    /**
     * Méthode de la classe Capteur, permet l'affectation de SN
     *
     * @param string $SN
     */
    public function setSN(string $SN): void
    {
        $this->SN = $SN;
    }

    /**
     * Méthode permetant de mettre à jour la conformité
     *
     *
     */


    static function UpdateValid(int $id)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET valid = 1
WHERE id = :id
SQL
            );
            $result->bindValue(':id', $id);
            $result->execute();

        } catch (Exception $e) {

        }
    }

    /**
     * Méthode permetant de mettre à jour la conformité
     *
     * @param int $id
     * @throws Exception
     */


    static function UpdateNotValid(int $id)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET valid = 0
WHERE id = :id
SQL
            );
            $result->bindValue(':id', $id);
            $result->execute();
        } catch (Exception $e) {

        }
    }

    /**
     * Méthode de la classe Capteur, créer un capteur et retourne son id
     *
     * @param int $idEssai
     * @param int $position
     * @return int
     * @throws Exception
     */
    static function createCapteurToDB(int $idEssai, int $position): int
    {

        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Capteur (idEssai, valid, position)
VALUES(:idEssai, 1, :position);
SQL
        );
        $result->bindValue(':idEssai', $idEssai);
        $result->bindValue(':position', $position);
        $result->execute();

        $result2 = MyPDO::getInstance()->prepare(<<<SQL
SELECT id AS 'nb' FROM Capteur WHERE position = :pos ORDER BY 1 DESC LIMIT 1;
SQL
        );
        $result2->bindValue(':pos', $position);
        $result2->execute();
        $result2 = $result2->fetch();
        return (int)$result2["nb"];

    }


    /**
     * Méthode de la classe Capteur, met à jour un capteur déja existant
     *
     * @param String $numLot
     * @param string $SN
     * @param int $idEssai
     * @param int $pos
     */
    static function UpdateCap(String $numLot, string $SN, int $idEssai, int $pos)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET idLot = (SELECT id 
        FROM Lot 
        WHERE numLot = :numLot),
        SN = :SN,
        HS = 0
WHERE idEssai = :idEssai AND position = :pos
SQL
            );
            $result->bindValue(':numLot', $numLot);
            $result->bindValue(':SN', $SN);
            $result->bindValue(':idEssai', $idEssai);
            $result->bindValue(':pos', $pos);
            $result->execute();
        } catch (Exception $e) {

        }
    }

    /**
     * Méthode de la classe Capteur, met à 0 SN et idLot
     *
     * @param int $idEssai
     * @throws Exception
     */
    static function UpdateCapSet0(int $idEssai)
    {

        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET idLot = NULL,
        SN = NULL,
        HS = 0
WHERE idEssai = :idEssai 
SQL
            );
            $result->bindValue(':idEssai', $idEssai);
            $result->execute();
        }catch (Exception $e){

        }
    }

    /**
     * Méthode de la classe Capteur, met à 0 SN et idLot
     * @param int $idEssai
     * @param int $pos
     */
    static function UpdateCapSetHS(int $idEssai, int $pos)
    {

        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Capteur SET HS = 1 
WHERE idEssai = :idEssai 
AND position = :pos
SQL
            );
            $result->bindValue(':idEssai', $idEssai);
            $result->bindValue(':pos', $pos);
            $result->execute();
        }catch (Exception $e){

        }
    }

    /**
     * Méthode de la classe Capteur, vérifie qu'un numéro de série n'est pas utilisé
     *
     * @param string $SN
     * @param int $idEssai
     * @return bool
     */
    static function VerifSN(string $SN, int $idEssai) : bool
    {

        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT count(id) AS "count"
FROM Capteur
WHERE SN = :SN
AND idEssai != :idEssai
SQL
            );
            $result->bindValue(':SN', $SN);
            $result->bindValue(':idEssai', $idEssai);
            $result->execute();
            $result = $result->fetch();
            $ret = true;
            if ($result["count"]==0) {
                $ret = false;
            }
            return $ret;
        }catch (Exception $e){

        }
    }

    /**
     * Méthode  de la classe Capteur, retourne une liste avec le nombre de capteurs non valides par position
     *
     * @return array|int[]
     * @throws Exception
     */
    static function getStatsPerPosition(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT DISTINCT position, COUNT(valid) AS "count"
FROM Capteur
WHERE valid = 0
GROUP BY position
SQL);
        $result->execute();
        $result = $result->fetchAll();
        $listret = [];
        foreach ($result AS $res) {
            $listret += [$res["position"]=>(int)$res["count"]];
        }
        return $listret;
    }


    /**
     *
     * Methode de la classe Capteur, permet de retourner le nom du produit en fonction de l'id Prduit dans Lot
     *
     * @return mixed
     */



    public function getProduct() {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT name FROM Produit
WHERE id = (SELECT idProduit FROM Lot
            WHERE id = :id)
SQL
        );
        $result->bindValue(':id', $this->idLot);
        $result->execute();
        $result = $result->fetch();
        return $result["name"];
    }



    /**
     *
     * Methode de la classe Capteur, permet de retourner la derniere position dans un essai
     *
     * @return mixed
     */

    static function getPosByidEssai(int $idEssai) {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MAX(position) as "pos" FROM Capteur
WHERE idEssai = :idEssai
SQL
        );
        $result->bindValue(':idEssai', $idEssai);
        $result->execute();
        $result = $result->fetch();
        return $result["pos"];
    }


    /**
     *
     * Methode de la classe Capeur, permet de retourner si un capteur si il est hs
     *
     * @param int $idCap
     * @return mixed
     */

    static function getHS(int $idCap): int {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT HS FROM Capteur
WHERE id = :idCap
AND HS = 1
SQL
        );
        $result->bindValue(':idCap', $idCap);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["HS"];
    }


    /**
     * Methode de la classe Capteur, permet de retourner l'id des capteurs en fonction de son id Essai
     *
     * @param int $id
     * @return array
     *
     */

static function getCapByIdessai(int $id): array
{
    $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT id FROM Capteur
WHERE idEssai = :id
AND HS = 0
SQL
    );
    $result->bindValue(':id', $id);
    $result->execute();
    $result = $result->fetchAll();
    $ret = [];
    foreach ($result as $res) {
        array_push($ret, (int)$res['id']);
    }
    return $ret;
}



}