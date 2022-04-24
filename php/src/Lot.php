<?php

declare(strict_types=1);

/**
 * Classe Lot
 */

class Lot
{
    const listNQA = [ 2=>6.5, 3=>4, 5=>2.5, 8=>1.5, 13=>1, 20=>0.65, 32=>0.40, 50=>1, 80=>1, 125=>1, 200=>1, 315=>1, 500=>1, 800=>1, 1250=>0.65 ];

    private $id;
    private $numLot;
    private $valid;
    private $idProduit;
    private $description;

    /**
     * Construteur de la classe lot.
     * @param int $id
     * @param string $numLot
     * @param int $valid
     * @param int $idProduit
     * @param string $description
     */
    public function __construct(int $id, string $numLot, int $valid, int $idProduit, string $description)
    {
        $this->id = $id;
        $this->numLot = $numLot;
        $this->valid = $valid;
        $this->idProduit = $idProduit;
        $this->description = $description;
    }

    /**
     * méthode statique de la classe Lot, permet d'obtenir tout les Lots
     *
     * @return array
     * @throws Exception
     */
    static function getAllLots(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Lot
ORDER BY id DESC
SQL
        );
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, new Lot((int)$res["id"], $res["numLot"], (int)$res["valid"], (int)$res["idProduit"], (string)$res["description"]));
        }
        return $ret;
    }

    /**
     * Méthode de la classe Lot, retourne le nom du produit
     *
     * @return string
     * @throws Exception
     */
    public function getProduct() :string{
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT name FROM Produit
WHERE id = :id
SQL
        );
        $result->bindValue(':id', $this->idProduit);
        $result->execute();
        $result = $result->fetch();
        return $result["name"];
    }

    /**
     * Méthode statique de la classe Lot, permet la récupération d'un lot avec son id
     *
     * @param int $id
     * @return Lot
     * @throws Exception
     */
    static function getLotFromID(int $id): Lot
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Lot
WHERE id = :id
SQL
        );
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Lot((int)$result["id"], $result["numLot"], (int)$result["valid"], (int)$result["idProduit"], (string)$result["description"]);
    }

    /**
     * Méthode statique de la classe Lot, permet la récupération d'un lot avec son numéro de lot
     *
     * @param string $num
     * @return Lot
     * @throws Exception
     */
    static function getLotFromNum(string $num): Lot
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Lot
WHERE numLot = :num
SQL
        );
        $result->bindValue(':num', $num);
        $result->execute();
        $result = $result->fetch();
        return new Lot((int)$result["id"], $result["numLot"], (int)$result["valid"], (int)$result["idProduit"], (string)$result["description"]);
    }

    /**
     * méthode statique de la classe Lot, permet d'obtenir tout les capteurs
     *
     * @return array
     * @throws Exception
     */

    public function getAllCapteurs(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Capteur
WHERE idLot = :id
ORDER BY SN
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, new Capteur((int)$res["id"], (int)$res["valid"], (int)$res["position"], (int)$res["idEssai"], (int)$res["idLot"], $res["SN"]));
        }
        return $ret;
    }

    /**
     * méthode statique de la classe Lot, permet d'obtenir tout les capteurs non conforme
     *
     * @return array
     * @throws Exception
     */

    public function getAllNotValidCapteurs(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Capteur
WHERE idLot = :id
AND valid=0
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $res) {
            array_push($ret, new Capteur((int)$res["id"], (int)$res["valid"], (int)$res["position"], (int)$res["idEssai"], (int)$res["idLot"], $res["SN"]));
        }
        return $ret;
    }

    /**
     * Méthode publique permettant la récupération d'un idEssai dans la table Posseder quand l'idLot est égale à l'id
     *
     * @return array
     * @throws Exception
     */
    public function getAllEssaisId(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT idEssai FROM Posseder
WHERE idLot = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $sql) {
            array_push($ret, (int)$sql["idEssai"]);
        }
        return $ret;
    }

    /**
     * Méthode publique de la classe Lot, permet la récupération du nombre de tubes Conforme
     *
     * @return int
     * @throws Exception
     */


    public function getNbNotValidCap(): int
    {
        $conforme = $this->getNbValidCap();
        $nb = $this->getNbCap();
        return $nb - $conforme;
    }

    /**
     * Méthode publique de la classe Lot, permet la récupération du nombre de tubes Conforme
     *
     * @return int
     * @throws Exception
     */


    public function getNbValidCap(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(id) AS 'nb'
FROM Capteur
WHERE valid = 1
AND idLot = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();

        return (int)$result["nb"];
    }

    /**
     * Méthode publique de la classe Lot, permet la récupération du nombre de tubes essayés
     *
     * @return int
     * @throws Exception
     */
    public function getNbCap(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(id) AS 'nb'
FROM Capteur
WHERE idLot = :id
SQL
        );
        $result->bindValue(':id', $this->id);
        $result->execute();
        $result = $result->fetch();

        return (int)$result["nb"];
    }

    /**
     * Accesseur de la classe Lot sur sa description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Accesseur de la classe Lot sur le type de produit
     *
     * @return string
     */
    public function getIdProduit(): int
    {
        return $this->idProduit;
    }

    /**
     * methode de la classe lot, permet la modification de la description
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * methode de la classe lot, permet la modification du type de produit
     *
     * @param string $idProduit
     */
    public function setIdProduit(string $idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    /**
     * Accesseur de la classe Lot sur son id
     *x
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * methode de la classe lot, permet la modification de l'id
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->idLot = $id;
    }

    /**
     * Accesseur de la classe Lot sur son numéro de lot
     *
     * @return string
     */
    public function getNumLot(): string
    {
        return $this->numLot;
    }

    /**
     * methode de la classe lot, permet la modification du numéro de lot
     *
     * @param string $numLot
     */
    public function setNumLot(string $numLot): void
    {
        $this->numLot = $numLot;
    }

    /**
     * Accesseur de la classe Lot sur sa conformité
     *
     * @return int
     */
    public function getValid(): int
    {
        return $this->valid;
    }

    /**
     * methode de la classe lot, permet la modification de la conformité
     *
     * @param int $valid
     */
    public function setValid(int $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * Accesseur de la classe Lot sur sa conformité
     *
     * @return string
     */
    public function getValidHTML(): string
    {
        $ret = "<p class='cred bold'>Non conforme &#10007;</p>";
        if ($this->valid == 1) {
            $ret = "<p class='cgreen bold'>conforme &#10003;</p>";
        }
        return $ret;
    }


    /**
     * Méthode permetant de supprimer un Lot
     *
     * @throws Exception
     */


    public function removeToDB()
    {
        try {
            var_dump($this->id);
            $sql = "DELETE FROM Lot WHERE id = :id ";
            $result = MyPDO::getInstance()->prepare($sql);
            $result->bindValue(':id', $this->id);
            $result->execute();
            header('Location: ../');
            exit();

        }catch (Exception $e){

        }
    }


    /**
     * Méthode de la classe Lot, permet d'obtenir l'id du lot
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function getIdLotCapteur(int $id)
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
    SELECT idLot FROM Capteur WHERE id = :id
    SQL
        );

        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return (int)$result["idLot"];
    }


    /**
     * Méthode de la classe Lot, permet de récuperer un lot de la table Posseder
     *
     * @param int $idEssai
     * @return array
     * @throws Exception
     */

    static function GetidLotEssai(int $idEssai){
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT idLot FROM Posseder WHERE idEssai = :idEssai 
SQL
        );
        $result->bindValue(':idEssai', $idEssai);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];
        foreach ($result as $sql) {
            array_push($ret, (int)$sql["idLot"]);
        }
        return $ret;
    }


    /**
     * Méthode de la classe Essai, permet de supprimer les lots de la table Posseder
     *
     * @param int $idEssai
     * @throws Exception
     */


    static function RemoveFromPosseder(int $idEssai){

        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Posseder WHERE idEssai = :idEssai 
SQL
            );
            $result->bindValue(':idEssai', $idEssai);
            $result->execute();
        }catch (Exception $e){

        }
    }


    /**
     * Méthode de la classe Lot, permet de recuperer le nombre d'idLot dans la table posseder
     *
     * @param int $idLot
     * @return int
     * @throws Exception
     */

    static function VerifIdLot(int $idLot){
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(idLot) AS 'nb' FROM Posseder WHERE idLot = :idLot
SQL
        );
        $result->bindValue(':idLot', $idLot);
        $result->execute();
        $result = $result->fetch();
        return (int) $result["nb"];

    }

    /**
     * Méthode de la classe Essai, permet de supprimer un lot de la table Posseder
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
     * Méthode de la classe Lot, permet de creer un lot
     *
     * @param string $numLot
     * @param int $valid
     * @param string $idProduit
     * @param string $description
     */

    static function createLot(string $numLot, int $valid, string $idProduit, string $description)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Lot(numLot, valid, description, idProduit) VALUES(:numLot, :valid, :description, :idProduit)
SQL);
            $result->bindValue(':numLot', $numLot);
            $result->bindValue(':valid', $valid);
            $result->bindValue(':description', $description);
            $result->bindValue(':idProduit', $idProduit);
            $result->execute();
        } catch (Exception $e) {
        }

    }

    /**
     * Méthode de la classe Essai, permet d'inserer un lot de la table Posseder
     *
     * @param string $numLot
     * @param int $idEssai
     * @param int $firstCap
     * @param int $lastCap
     */

    static function insertToPosseder(string $numLot, int $idEssai, int $firstCap, int $lastCap)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Posseder(idLot, idEssai, firstCap, lastCap) VALUES((SELECT id 
                                                                FROM Lot 
                                                                WHERE numLot = :numLot),
                                                               :idEssai, 
                                                               :firstCap, 
                                                               :lastCap)
SQL);
            $result->bindValue(':firstCap',$firstCap);
            $result->bindValue(':lastCap',$lastCap);
            $result->bindValue(':numLot',$numLot);
            $result->bindValue(':idEssai',$idEssai);
            $result->execute();
        } catch (Exception $e) {
        }

    }

    /**
     * Méthode de la classe Lot, met à jour valid suivant le nombre de produit essayé
     *
     * @throws Exception
     */

    public function updateValid() {
        $nbCap = $this->getNbCap();
        $nbNotValidCap = $this->getNbNotValidCap();
        $valid = 0;

        if ($nbCap == 1250 && $nbNotValidCap<22) {
            $valid = 1;
        }
        if ($nbCap == 800 && $nbNotValidCap<15) {
            $valid = 1;
        }
        if ($nbCap == 500 && $nbNotValidCap<11) {
            $valid = 1;
        }
        if ($nbCap == 315 && $nbNotValidCap<8) {
            $valid = 1;
        }
        if ($nbCap == 200 && $nbNotValidCap<6) {
            $valid = 1;
        }
        if ($nbCap == 125 && $nbNotValidCap<4) {
            $valid = 1;
        }
        if ($nbCap == 80 && $nbNotValidCap<3) {
            $valid = 1;
        }
        if ($nbCap == 50 && $nbNotValidCap<2) {
            $valid = 1;
        }
        if ($nbCap == 32 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 20 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 13 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 8 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 5 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 3 && $nbNotValidCap<1) {
            $valid = 1;
        }
        if ($nbCap == 2 && $nbNotValidCap<1) {
            $valid = 1;
        }
        $this->valid = $valid;
        $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Lot SET valid = :valid WHERE id = :id
SQL);
        $result->bindValue(":valid", $valid);
        $result->bindValue(":id", $this->id);
        $result->execute();
    }

    /**
     * Méthode de la classe Lot, retourne le NQA
     *
     * @return int
     * @throws Exception
     */
    public function getNQA(): int
    {
        $pos = $this->getNbCap();
        return (int)Lot::listNQA["$pos"];
    }

    /**
     * Méthode de la classe Lot, met à jour la description
     *
     * @param int $id
     * @param string $desc
     * @throws Exception
     */
    static function updateDesc(int $id, string $desc){
        $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Lot SET description = :description WHERE id = :id
SQL);
        $result->bindValue(":description", $desc);
        $result->bindValue(":id", $id);
        $result->execute();
    }


    /**
     * Méthode de la classe Lot, récupere le nombre de Lot en fonction du type de produit
     *
     * @return array
     * @throws Exception
     */
    static function getNbLotPerProduct(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT DISTINCT p.name AS "product", COUNT(l.id) AS "count"
FROM Produit p LEFT JOIN Lot l ON l.idProduit = p.id
GROUP BY p.name
ORDER BY 1, 2
SQL
        );
        $result->execute();
        $result = $result->fetchAll();
        return $result;
    }

    /**
     * Méthode de la classe Lot, récupere le nombre de capteurs non valides dans un lot
     *
     * @return array
     * @throws Exception
     */
    static function getNbErrorPerLot(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT DISTINCT l.numLot as "numLot", COUNT(c.valid) AS "count"
FROM Capteur c , Lot l
WHERE l.id = c.idLot
AND c.valid = 0
GROUP BY c.idLot
ORDER BY 1, 2
SQL
        );

        $result->execute();
        $result = $result->fetchAll();
        return $result;

    }

    /**
     * Méthode de la classe Lot, récupere le temps minimum des essais d'un lot
     *
     * @return int
     * @throws Exception
     */
    public function getFirstDateEssai(): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT MIN(time) AS "time"
FROM Essai
WHERE id IN(SELECT idEssai FROM Posseder
            WHERE idLot = :id)
SQL
        );
        $result->bindValue(":id", $this->id);
        $result->execute();
        $result = $result->fetch();
        return (int) $result["time"];
    }


    /**
     * Méthode permetant d'avoir tout les produits
     *
     * @return array
     * @throws Exception
     */

    static function getAllProduits(): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT id AS "ID", name AS "NAME", seuil AS "SEUIL"
FROM Produit
SQL
        );
        $result->execute();
        $result = $result->fetchAll();
        return $result;
      
    }


    /**
     * Méthode permetant d'avoir tout les produits
     *
     * @param int $id
     * @return array
     * @throws Exception
     */

    static function getProduitidEssai(int $id): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT name AS "nameProduct" FROM Produit WHERE id = :id
SQL
        );

        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $product) {
            array_push($ret,($product["nameProduct"]));
        }
        return $ret;
    }


    /**
     * Méthode permetant de mettre à jour un produit
     *
     * @param int $id
     * @param string $nameProduct
     * @param int $seuil
     */

    static function updateProduct(int $id, string $nameProduct, int $seuil){
        $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Produit SET name = :nameProduct, seuil = :seuil WHERE id = :id
SQL);
        $result->bindValue(":nameProduct", $nameProduct);
        $result->bindValue(":id", $id);
        $result->bindValue(":seuil", $seuil);
        $result->execute();
    }

    /**
     * Méthode de la classe Lot, permet de creer un produit
     *
     * @param string $product
     * @param int $seuil
     */

    static function createProduct(string $product, int $seuil)
    {

            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Produit(name, seuil) VALUES(:name, :seuil)
SQL);
            $result->bindValue(':name', $product);
            $result->bindValue(':seuil', $seuil);
            $result->execute();


    }

    /**
     * Méthode de la classe Lot, permet de supprimer un produit
     *
     * @param int $id
     */

    static function SupprProduct(int $id)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Produit WHERE id = :id
SQL);
            $result->bindValue(':id', $id);
            $result->execute();
        } catch (Exception $e) {
        }

    }


    /**
     * Méthode permetant de recuperer le seuil
     *
     * @param int $idProduit
     * @return int
     */

    static function GetSeuil(int $idProduit){
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT seuil FROM Produit WHERE id = :idProduit
SQL);
        $result->bindValue(":id", $idProduit);
        $result->execute();
        $result = $result->fetch();
        return (int)$result['seuil'];
    }

    /**
     * Méthode statique de la classe Lot, permet la récupération d'un lot avec son idEssai
     *
     * @param int $idEssai
     * @return array
     */
    static function getLotFromIDEssai(int $idEssai): array
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT idLot FROM Posseder
WHERE idEssai = :id
SQL
        );
        $result->bindValue(':id', $idEssai);
        $result->execute();
        $result = $result->fetchAll();
        $ret = [];

        foreach ($result as $product) {
            array_push($ret,($product["idLot"]));
        }
        return $ret;
    }

    /**
     * Méthode statique de la classe Lot, permet la récupération d'un du seuil en fonction de l'idProduit et de l'idLot associé
     *
     * @param int $idEssai
     * @return array
     */
    static function GetSeuilFromIdLot(int $idLot): int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT (SELECT seuil FROM Produit WHERE id = idProduit) as Seuil
FROM Lot 
WHERE id = :idLot
SQL
        );
        $result->bindValue(':idLot', $idLot);
        $result->execute();
        $result = $result->fetch();
        return (int)$result['Seuil'];

    }


    /**
     * Methode static, permettant de retourner le nombre d'id essai contenu dans posseder en fonction de l'idLot
     * @param int $idLot
     * @return int
     */


    static function GetnbEssaiLot(int $idLot){

        $result = MyPDO::getInstance()->prepare(<<<SQL
    SELECT COUNT(idEssai) as "count"
    FROM Posseder 
    WHERE idLot = :idLot
  SQL
        );
        $result->bindValue(':idLot', $idLot);
        $result->execute();
        $result = $result->fetch();
        return (int)$result['count'];

    }




}