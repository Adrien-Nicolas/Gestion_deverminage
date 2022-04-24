<?php

declare(strict_types=1);




class Valeur
{
    private $id;
    private $time;
    private $value;
    private $valid;


    /**
     * Constructeur de la classe Valeur
     *
     * @param int $id
     * @param int $time
     * @param int $value
     * @param int $valid
     */


    public function __construct(int $id, int $time, int $value, int $valid)
    {
        $this->id = $id;
        $this->time = $time;
        $this->value = $value;
        $this->valid = $valid;

    }


    /**
     * Méthode de la classe Valeur qui permet la création d'une valeur avec son ID
     *
     * @param int $id
     * @return Valeur
     */

    static function getValeurFromId(int $id): Valeur
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * 
FROM Valeur
WHERE id = :id;
SQL
            );
        } catch (Exception $e) {
        }
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Valeur($id, (int)$result["time"], (int)$result["value"], (int)$result["valid"], (int)$result["idCap"]);
    }

    /**
     * Méthode permetant d'avoir toutes les valeurs
     *
     * @return array
     * @throws Exception
     */


    static function getAllValeur(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT value FROM Valeur 
SQL
        );
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $val) {
            array_push($ret, new Valeur((int)$val["id"], (int)$val["time"], (int)$val["value"], (int)$val["valid"]));
        }
        return $ret;
    }

    /**
     * Méthode permetant de supprimer les valeurs
     *
     * @param int $id
     */

    static function removeToDB(int $id)
    {
        try {
            $sql = "DELETE * FROM Valeur WHERE id = :id; ";
            $result = MyPDO::getInstance()->prepare($sql);
            $result->bindValue(':id', $id);
            $result->execute();
        } catch (Exception $e) {
        }

    }


    /**
     * Méthode de la classe Valeur, permet l'obtention du Time
     *
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }


    /**
     * Méthode de la classe Valeur, permet l'obtention de l'ID
     *
     * @return int
     */
    public function getIdValue(): int
    {
        return $this->id;
    }

    /**
     * Méthode de la classe Valeur, permet l'obtention de la valeur
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Méthode de la classe Valeur, permet l'obtention de l'ID capteur
     *
     * @return int
     */
    public function getIDCap(): int
    {
        return $this->idCap;
    }


    /**
     * Méthode de la classe Valeur, permet l'obtention de valid
     *
     * @return int
     */
    public function getValid(): int
    {
        return $this->valid;
    }

    /**
     * Méthode de la classe Valeur, permet l'affectation de valid
     *
     * @param int $valid
     */
    public function setValid(int $valid): void
    {
        $this->valid = $valid;
    }


    /**
     * Méthode de la classe Valeur, permet l'affectation de valeur
     *
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     *Méthode de la classe Valeur permettant d'inserer des données dans la table Valeur
     * @param int $value
     * @param int $time
     * @param int $valid
     * @param int $idCap
     */
    static function uploadValeur(int $time, int $value, int $valid, int $idCap): void
    {
            $sql = "INSERT INTO `Valeur` (`time`, `value`, `valid`, `idCap`) VALUES (:time , :value , :valid, :idcap); ";
            $result = MyPDO::getInstance()->prepare($sql);
            $result->bindValue(':value', $value);
            $result->bindValue(':time', $time);
            $result->bindValue(':valid', $valid);
            $result->bindValue(':idcap', $idCap);
            $result->execute();
    }


    /**
     * Méthode permetant d'avoir la valeur la plus basse en fonction de l'id Cap
     *
     * @param int $idCap
     * @return int
     */


    static function getMinValFromIdCap(int $idCap): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MIN(value) AS "value" FROM Valeur 
WHERE idCap = :idCap
AND value != 0
SQL
        );
        $result->bindValue(':idCap', $idCap);
        $result->execute();
        $result = $result->fetch();

        return (int)$result['value'];
    }

    /**
     * Méthode permetant d'avoir la valeur la plus Haute en fonction de l'id Cap
     *
     * @param int $idCap
     * @return int
     */


    static function getMaxValFromIdCap(int $idCap): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MAX(value) AS "value" FROM Valeur 
WHERE idCap = :idCap
SQL
        );
        $result->bindValue(':idCap', $idCap);
        $result->execute();
        $result = $result->fetch();

        return (int)$result['value'];
    }



    

}

