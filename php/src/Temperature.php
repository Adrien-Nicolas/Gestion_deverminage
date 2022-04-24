<?php

declare(strict_types=1);

class Temperature
{
    private $id;
    private $idEssai;
    private $time;
    private $value;

    /**
     * Temperature constructor.
     * @param $id
     * @param $idEssai
     * @param $time
     * @param $value
     */
    public function __construct(int $id, int $idEssai, int $time, float $value) {
        $this->id = $id;
        $this->idEssai = $idEssai;
        $this->time = $time;
        $this->value = $value;
    }

    /**
     * Méthode de la classe Temperature, créer un objet de classe température avec les données de la base de donnée
     *
     * @param int $id
     * @return Temperature
     * @throws Exception
     */
    static function getTempFromID(int $id): Temperature
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Temperature
WHERE id = :id
SQL
        );
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Temperature((int)$result["id"], (int)$result["idEssai"], (int)$result["time"], (float)$result["value"]);
    }

    /**
     * Accesseur de la classe Temperature sur l'id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Accesseur de la classe Temperature sur l'idEssai
     *
     * @return int
     */
    public function getIdEssai(): int
    {
        return $this->idEssai;
    }

    /**
     * Accesseur de la classe Temperature sur time
     *
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Accesseur de la classe Temperature sur la valeur
     *
     * @return int
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Modificateur de la classe Temperature sur l'idEssai
     *
     * @param int $idEssai
     */
    public function setIdEssai(int $idEssai): void
    {
        $this->idEssai = $idEssai;
    }

    /**
     * Modificateur de la classe Temperature sur le temps
     *
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * Modificateur de la classe Temperature sur la valeur
     *
     * @param int $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * Méthode statique de la classe Temperature, récupere les valeurs qui ont l'id essai qui à été mis en parametre
     *
     * @param int $idEssai
     * @return array
     * @throws Exception
     */
    static function getAllValueFromIdEssai(int $idEssai) {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Temperature
WHERE idEssai = :id
SQL
        );
        $result->bindValue(':id', $idEssai);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, new Temperature((int)$res["id"], (int)$res["idEssai"], (int)$res["time"], (float)$res["value"]));
        }
        return $ret;
    }

    /**
     * Méthode statique de la classe Temperature, insert une température dans la base de données
     *
     * @param int $idEssai
     * @param int $time
     * @param int $value
     * @throws Exception
     */
    static function createTempToDB(int $idEssai, int $time, float $value) {
        $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Temperature(idEssai, time, value) VALUES (:idEssai, :time, :value)
SQL
        );
        $result->bindValue(':idEssai', $idEssai);
        $result->bindValue(':time', $time);
        $result->bindValue(':value', $value);
        $result->execute();
    }

    /**
     * Méthode statique de la classe Temperature, crée un objet Temperature, récupere les données d'une température dans la base de données
     *
     * @param int $idEssi
     * @return Temperature
     */


    static function getTempidEssai(int $id): Temperature
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Temperature
WHERE idEssai = :id
SQL
        );
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Temperature((int)$result["id"], (int)$result["idEssai"], (int)$result["time"], (float)$result["value"]);
    }



    /**
     * Méthode de la classe Temperature, permet l'obtention valeurs
     *
     * @return array
     */
    public function getValues()
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Temperature 
WHERE idEssai = :id
ORDER BY time, value ASC 
LIMIT 1000000
SQL
        );
        $result->bindValue(':id', $this->idEssai);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $val) {
            array_push($ret, new Temperature((int)$val["id"],(int)$val["idEssai"], (int)$val["time"], (float)$val["value"]));
        }
        return $ret;
    }

    /**
     * Méthode de la classe Temperature, permet l'obtention de la derniere valeur de temperature
     *
     * @return float
     */


static function getlastTemp(): float
{

    $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT value FROM Temperature WHERE id = (SELECT MAX(id) as id FROM Temperature )
SQL
    );
    $result->execute();
    $result = $result->fetch();
    return (float)$result['value'];






    }



}