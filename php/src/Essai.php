<?php

declare(strict_types=1);

class Essai
{
    private $id;
    private $date;
    private $idOp;
    private $valid;
    private $config;


    /**
     * Constructeur de la classe Essai
     *
     * @param int $id
     * @param int $date
     * @param int $idOp
     * @param int $valid
     * @param int $config
     */
    public function __construct(int $id, int $date, int $idOp, int $valid, int $config)
    {
        $this->id = $id;
        $this->date = $date;
        $this->idOp = $idOp;
        $this->valid = $valid;
        $this->config = $config;
    }

    /**
     * Méthode de la classe Essai, permet de créer un utilisateur avec son id
     *
     * @param int $id
     * @return Essai
     * @throws Exception
     */
    static function getEssaiFromId(int $id)
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Essai 
WHERE id = :id
SQL
        );
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Essai((int)$result["id"], (int)$result["time"], (int)$result["idOp"], (int)$result["valid"], (int)$result["config"]);
    }

    /**
     * Méthode de la classe Essai, permet de récuperer tout les essais
     *
     * @return array
     * @throws Exception
     */
    static function getAllEssais()
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Essai 
ORDER BY id DESC
SQL
        );
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, new Essai((int)$res["id"], (int)$res["time"], (int)$res["idOp"], (int)$res["valid"],  (int)$res["config"]));
        }
        return $ret;
    }

    /**
     * Accesseur de la classe Essai sur l'id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Accesseur de la classe Essai sur la date
     *
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * Accesseur de la classe Essai sur la configuration
     *
     * @return int
     */
    public function getConfig(): int
    {
        return $this->config;
    }

    /**
     * Accesseur de la classe Essai sur la date en string
     *
     * @param bool $ms
     * @return string
     */
    public function getDateString(bool $ms = false): string
    {
        $GMT = +2;
        $date = gmdate("d / m / Y à H:i:s", (int)round($this->date / 1000) + ($GMT * 3600));
        if ($ms) {
            $date .= ' ' . substr((string)$this->date, 9, -1) . 'ms';
        }
        return $date;
    }

    /**
     * Méthode permetant de récuperer tout les capteurs
     *
     * @return array
     * @throws Exception
     */
    public function getAllCapteurs(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Capteur
WHERE id = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, Capteur::getCapteurFromId((int)$res["idCap"]));
        }
        return $ret;
    }

    /**
     * Accesseur de la classe Essai sur l'id de l'operateur
     *
     * @return int
     */
    public function getIdOp(): int
    {
        return $this->idOp;
    }

    /**
     * Accesseur de la classe Essai sur la conformité d'un essai
     *
     * @return int
     */
    public function getValid(): int
    {
        return $this->valid;
    }


    /**
     * Accesseur de la classe Essai sur sa conformité
     *
     * @return string
     */
    public function getValidHTML(): string
    {
        $ret = "<p class='bold'>Non conforme &#10007;</p>";
        if ($this->valid == 1) {
            $ret = "<p class='cgreen bold' style='padding-left: 2em'>conforme &#10003;</p>";
        }
        return $ret;
    }

    /**
     * Méthode de la classe Essai, retourne si un essai est valide ou non
     *
     * @param int $valid
     */
    public function setValid(int $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * Modificateur de la classe Essai sur l'id
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Modificateur de la classe Essai sur la date
     *
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * Modificateur de la classe Essai sur l'id de l'operateur
     *
     * @param int $idOp
     */
    public function setIdOp(int $idOp): void
    {
        $this->idOp = $idOp;
    }


    /**
     *
     * Methode de la classe Essai, permet de recuperer les lots en fonction de l'essai
     *
     * @return array
     */


    public function getLots()
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Posseder
WHERE idEssai = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, Lot::getLotFromID((int)$res["idLot"]));
        }
        return $ret;
    }

    /**
     * Méthode de la classe Essai, permet de récupérer la place du premier capteur
     *
     * @param int $id id du lot
     * @return mixed
     * @throws Exception
     */
    public function getFirstCap(int $id)
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT firstCap FROM Posseder
WHERE idEssai = :id
AND idlot = :id2
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->bindValue(':id2', $id);
        $result->execute();
        $result = $result->fetch();
        return $result["firstCap"];
    }

    /**
     * Méthode de la classe Essai, permet de récupérer la place du dernier capteur
     *
     * @param int $id id du lot
     * @return mixed
     * @throws Exception
     */
    public function getLastCap(int $id)
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT lastCap FROM Posseder
WHERE idEssai = :id
AND idLot = :id2
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->bindValue(':id2', $id);
        $result->execute();
        $result = $result->fetch();
        return $result["lastCap"];
    }

    /**
     * Méthode de la classe Essai, permet de modifier la place du premier capteur
     *
     * @param int $id id du lot
     * @param int $pos
     * @throws Exception
     */
    public function setFirstCap(int $id, int $pos)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Posseder SET first_cap = :pos
WHERE id = :id
AND idLot = :id2
SQL
            );
            $result->bindValue(':id', $this->id);
            $result->bindValue(':id2', $id);
            $result->bindValue(':pos', $pos);
            $result->execute();
        }catch (Exception $e){

        }
    }

    /**
     * Méthode de la classe Essai, permet de modifier la place du dernier capteur
     *
     * @param int $id id du lot
     * @param int $pos
     * @throws Exception
     */
    public function setLastCap(int $id, int $pos)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Posseder SET lastCap = :pos
WHERE id = :id
AND idLot = :id2
SQL
            );
            $result->bindValue(':id', $this->id);
            $result->bindValue(':id2', $id);
            $result->bindValue(':pos', $pos);
            $result->execute();
        }catch (Exception $e){

        }
    }

    /**
     * Méthode de la classe Essai, permet d'obtenir la date du dernier capteur
     *
     * @return int
     * @throws Exception
     */
    public function getLastCapDate(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MAX(v.time) AS val
FROM Valeur v, Capteur c
WHERE v.idCap = c.id
AND idEssai = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        if ($result["val"] == NULL) {
            return $this->date;
        }
        return $this->date + (int)$result["val"];
    }

    /**
     * Méthode de la classe Essai, retourne la date sous forme de String
     *
     * @return false|string
     * @throws Exception
     */
    public function getLastCapDateString()
    {
        $GMT = +2;
        return gmdate("d / m / Y à H:i:s", (int)round($this->getLastCapDate() / 1000) + (3600 * $GMT));
    }

    /**
     * Méthode de la classe Essai, retourne le nombre de lots valides
     *
     * @return int
     * @throws Exception
     */
    public function getNbValidLot(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(l.valid) AS nb
FROM Lot l, Posseder p
WHERE p.idLot = l.id
AND p.idEssai = :id
AND l.valid = 1
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["nb"];
    }

    /**
     * Méthode de la classe Essai, retourne le nombre de lots non valides
     *
     * @return int
     * @throws Exception
     */
    public function getNbNotValidLot(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(l.valid) AS nb
FROM Lot l, Posseder p
WHERE p.idLot = l.id
AND p.idEssai = :id
AND l.valid = 0
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["nb"];
    }

    /**
     * Méthode de la classe Essai, retourne le nombre de lots
     *
     * @return int
     * @throws Exception
     */
    public function getNbLot(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(l.valid) AS nb
FROM Lot l, Posseder p
WHERE p.idLot = l.id
AND p.idEssai = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["nb"];
    }

    /**
     * Méthode de la classe Essai, retourne le nombre de capteurs non valides
     *
     * @return int
     * @throws Exception
     */
    public function getNbCapNotValid()
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
    SELECT COUNT(id) AS nb
    FROM Capteur
    WHERE idEssai = :id
    AND valid = 0
    SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["nb"];
    }

    /**
     * Méthode de la classe Essai, retourne le nombre de capteurs
     *
     * @return int
     * @throws Exception
     */
    public function getNbCap(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
    SELECT COUNT(id) AS nb
    FROM Capteur
    WHERE idEssai = :id
    SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["nb"];
    }

    /**
     * Méthode de la classe Essai, met à jour valide
     *
     * @throws Exception
     */
    public function updateValid()
    {
        try {

            if ($this->getNbCapNotValid() == 0 && $this->getNbCap() > 0) {
                $this->valid = 1;
                $result = MyPDO::getInstance()->prepare(<<<SQL
    UPDATE Essai SET valid = 1 WHERE id = :id
    SQL
                );
                $result->bindValue(':id', $this->id);
                $result->execute();
            }

            if ($this->getNbCapNotValid() >= 1 && $this->getNbCap() > 0) {
                $this->valid = 0;
                $result = MyPDO::getInstance()->prepare(<<<SQL
    UPDATE Essai SET valid = 0 WHERE id = :id
    SQL
                );
                $result->bindValue(':id', $this->id);
                $result->execute();
            }
        }catch(Exception $e){

        }
    }

    /**
     * Méthode de la classe Essai, créer un essai dans la base de données
     *
     * @return int
     * @throws Exception
     */
    static function createEssaiToDB(): int
    {

          $current_timestamp = time() * 1000;
          $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Essai (time, valid)
VALUES(:time, 1);
SQL
          );
          $result->bindValue(':time', $current_timestamp);
          $result->execute();

          $result2 = MyPDO::getInstance()->prepare(<<<SQL
SELECT LAST_INSERT_ID() as 'nb';
SQL
          );
          $result2->execute();
          $result2 = $result2->fetch();
          return (int)$result2["nb"];


}

    /**
     * Méthode de la classe Essai, retourne le dernier id essai, si il à été créé il y à moins de 5 minutes
     *
     * @return int
     * @throws Exception
     */
    static function getIdLastFiveMinEssai(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT id FROM Essai WHERE time > (UNIX_TIMESTAMP() - (60*5))*1000 ORDER BY 1 DESC LIMIT 1
SQL
        );
        $result->execute();
        $result = $result->fetch();
        return (int)$result["id"];
    }



    /**
     * Méthode de la classe Essai, supprime une ligne de la table posseder
     *
     * @param int $idLot
     * @throws Exception
     */
    static function RemoveFromPosseder(int $idLot){
        $result = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Posseder WHERE idLot = :idLot
SQL
        );
        $result->bindValue(':idLot', $idLot);
        $result->execute();
    }


    /**
     * Méthode de la classe Essai, compte le nombre de ligne dans la table posseder avec l'idLot
     *
     * @param int $idLot
     * @return int
     * @throws Exception
     */
    static function VerifIdLot(int $idLot ){
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(idLot) FROM Posseder WHERE idLot = :idLot
SQL
        );
        $result->bindValue(':idLot', $idLot);
        $result->execute();
        $result = $result->fetch();
        return (int) $result;
    }


    /**
     * Méthode de la classe Essai, retire un lot de la base de données
     *
     * @param int $id
     * @throws Exception
     */
    static function removeToDBLot(int $id)
    {
        $sql = "DELETE FROM Lot WHERE id = :id ";
        $result = MyPDO::getInstance()->prepare($sql);
        $result->bindValue(':id',$id);
        $result->execute();
    }


    /**
     * Méthode de la classe Essai, permet de creer un lot
     *
     * @param String $numLot
     */


    static function createLot(String $numLot)
    {
        try {
            $sql = "INSERT INTO Lot(numLot) VALUES(:numLot)";

            $result = MyPDO::getInstance()->prepare($sql);
            $result->bindValue(':numLot', $numLot);
            $result->execute();
        } catch (Exception $e) {
        }

    }

    /**
     * Méthode de la classe Essai, récupere le numéro de série de départ
     *
     * @param String $numLot
     * @return mixed
     */
    public function getSNBegin(String $numLot) {
        try {

            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MIN(SN) AS "min"
FROM Capteur
WHERE idLot = (SELECT id FROM Lot WHERE numLot = :numLot)
AND idEssai = :idEssai
SQL);
            $result->bindValue(':numLot', $numLot);
            $result->bindValue(':idEssai', $this->id);
            $result->execute();
        } catch (Exception $e) {
        }
        $result = $result->fetch();
        return $result["min"];
    }

    /**
     * Méthode de la classe Essai, met à jour config et l'id de l'opérateur
     *
     * @param int $config
     * @param int $idOp
     */
    public function updateEssai(int $config, int $idOp){
        try {

            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Essai SET config = :config, idOp = :idOp WHERE id = :idEssai
SQL);
            $result->bindValue(':config', $config);
            if ($this->idOp){
                $idOp = $this->idOp;
            }
            $result->bindValue(':idOp', $idOp);
            $result->bindValue(':idEssai', $this->id);
            $result->execute();
        } catch (Exception $e) {
        }
    }



    /**
     * Méthode de la classe Essai, Supprime un essai
     *
     *
     */
    public function RemovetoDB(){
            $result = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Essai WHERE id = :id
SQL);
            $result->bindValue(':id', $this->id);
            $result->execute();
    }


    /**
     * Méthode de la classe Essai, récupere les numéro de série
     *
     * @param String $numLot
     * @return mixed
     * @throws Exception
     */
    static function getAllSN(): array
    {

        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT SN as "nb"
FROM Capteur
SQL
        );

        $result->execute();
        $result = $result->fetchAll();
        $ret = [];

        foreach ($result as $sql) {
          
            array_push($ret, (int)$sql["nb"]);
        }
        return $ret;
    }


    /**
     *
     * Methode de la classe Essai, permet de récuperer la position d'un capteur en fonction de la validité, du hors service = 0 et de l'essai
     *
     * @return array
     */


    public function getNotValidCap() :array{
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT position as "pos"
FROM Capteur
WHERE valid = 0
AND HS = 0
AND idEssai = :id
SQL
        );
        $result->bindValue(":id", $this->id);
        $result->execute();
        $result = $result->fetchAll();
        $ret = [];

        foreach ($result as $sql) {

            array_push($ret, (int)$sql["pos"]);
        }
        return $ret;
    }

    /**
     *
     * Methode de la classe Essai, permet de récuperer la position d'un capteur en fonction de la validité, du hors service = 1 et de l'essai
     *
     * @return mixed
     */


    public function getPosCapHS() {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT position as "pos"
FROM Capteur
WHERE HS = 1
AND idEssai = :id
SQL
        );
        $result->bindValue(":id", $this->id);
        $result->execute();
        return $result->fetchAll();
    }



    /**
     * Méthode de la classe Essai permettant de récuperer le nombre de valeur de l'essai
     *
     * @return int
     */

    public function getNbVal(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
    SELECT count(v.id) AS "count"
    FROM Valeur v, Capteur c
    WHERE v.idCap = c.id
    AND c.idEssai = :id
    SQL
        );
        $result->bindValue(":id", $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["count"];
    }

    /**
     *Methode de la classe Essai, permet de retourner l'idEssai en fonction de l'idLot dans la table Posseder
     * @param int $idLot
     * @return int
     */
    static function getEssaiFromLot(int $idLot): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
      SELECT idEssai 
      FROM Posseder
    WHERE  idLot = :idLot
SQL
        );
        $result->bindValue(":idLot", $idLot);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["idEssai"];
    }



    static function getlastEssai(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
 SELECT MAX(id) AS idEssai
FROM Essai
SQL
        );
        $result->execute();
        $result = $result->fetch();
        return (int)$result["idEssai"];
    }

}