<?php

declare(strict_types=1);

class Utilisateur
{
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $nickname;
    private $role;
    private $password;
    private $vkey;

    /**
     * Constructeur de la classe Utilisateur
     *
     * @param int $id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $nickname
     * @param string $role
     * @param string $password
     * @param string $vkey
     */
    public function __construct(int $id, string $firstname, string $lastname, string $email, string $nickname, string $role, string $password, string $vkey) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->nickname = $nickname;
        $this->role = $role;
        $this->password = $password;
        $this->vkey = $vkey;
    }

    /**
     * Méthode de la classe Utilisateur qui permet la création d'un utilisateur avec son ID
     *
     * @param int $id
     * @return Utilisateur
     */
    static function getUtilisateurFromId(int $id) : Utilisateur{
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * 
FROM Utilisateur
WHERE id = :id;
SQL
            );
        } catch (Exception $e) {
        }
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new Utilisateur($id, $result["firstname"], $result["lastname"], $result["email"], $result["nickname"], $result["role"], $result["password"], $result["vkey"]);
    }

    /**
     * Méthode permetant d'avoir tous les utilisateurs
     *
     * @return array
     */

    static function getAllUsers() {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * FROM Utilisateur
SQL
        );
        $result->execute();
        $result = $result->fetchAll();

        $ret = [];

        foreach ($result as $user) {
            array_push($ret, new Utilisateur((int) $user["id"], $user["firstname"], $user["lastname"], $user["email"], $user["nickname"], $user["role"], $user["password"], $user["vkey"]));
        }
        return $ret;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention du prénom
     *
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention de l'ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention du non
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention du pseudo
     *
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention du role
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention de l'email
     *
     * @return string
     */

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'obtention de la vkey
     *
     * @return string
     */
    public function getVkey(): string
    {
        return $this->vkey;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'affectation du prénom
     *
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'affectation du nom
     *
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'affectation du pseudo
     *
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickName = $nickname;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'affectation du mot de passe
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Méthode de la classe Utilisateur, permet l'affectation du role
     *
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Méthode de la classe Utilisateur, permet le changement de vkey
     */
    public function setVkey(): void
    {
        $this->vkey = $vkey = md5(time().$this->nickname);
    }

    /**
     * Methode de la classe utilisateur qui permet la création d'un nouvel utilisateur dan sla base de données
     * @param string $lastname
     * @param string $firstname
     * @param string $email
     * @param string $nickname
     * @param string $role
     * @param string $password
     */

    static function createUser(string $lastname, string $firstname,string $email, string $nickname, string $role, string $password)
    {

        try {

            $vkey = md5($nickname . time());
            $result = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Utilisateur(firstname, lastname, email, role, nickname, password, Vkey) VALUES(:firstname, :lastname, :email, :role, :nickname, :password, :vkey)
SQL
            );

            $result->bindValue(':firstname', $firstname);
            $result->bindValue(':lastname', $lastname);
            $result->bindValue(':email', $email);
            $result->bindValue(':role', $role);
            $result->bindValue(':password', $password);
            $result->bindValue(':nickname', $nickname);
            $result->bindValue(':vkey', $vkey);
            $result->execute();

        }catch (Exception $e){

        }
    }


    /**
     * Methode de la classe utilisateur qui permet la modification d'un  utilisateur dans la base de données
     * @param int $id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $role
     * @param string $nickname
     * @param string $password
     */

    static function updateUser( int $id, string $firstname,string $lastname,string $email, string $role, string $nickname, string $password)
    {

        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Utilisateur SET firstname = :firstname, lastname = :lastname, email = :email, role = :role, nickname = :nickname, password = :password
WHERE id = :id
SQL
            );

            $result->bindValue(':id', $id);
            $result->bindValue(':firstname', $firstname);
            $result->bindValue(':lastname', $lastname);
            $result->bindValue(':email', $email);
            $result->bindValue(':role', $role);
            $result->bindValue(':password', $password);
            $result->bindValue(':nickname', $nickname);
            $result->execute();
        }catch (Exception $e){

        }
    }



    /**
     * Methode de la classe utilisateur qui permet la suppression d'un  utilisateur dans la base de données
     * @param int $id
     *
     */

    static function deleteUser($id)
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Utilisateur WHERE id = :id
SQL
            );
            $result->bindValue(':id', $id);
            $result->execute();

        }catch (Exception $e){
            
        }
    }

    /**
     * Méthode de la classe Utilisateur, récupere le nombre d'essai effextuer par opérateur
     *
     * @return array|false|PDOStatement
     * @throws Exception
     */
    static function getNbEssaiPerUser()
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT DISTINCT u.lastname, u.firstname, COUNT(e.id) AS "count"
FROM Essai e 
    RIGHT JOIN Utilisateur u 
        ON u.id = e.idOp
GROUP BY u.lastname, u.firstname
ORDER BY 1, 2
SQL
        );
        $result->execute();
        $result = $result->fetchAll();
        return $result;
    }


    /**
     * Méthode de la classe Utilisateur, permettant de recupérer l'idOp en fonction de l'id Essai
     * @param int $idEssai
     * @return int
     */

    static function getUserByidEssai(int $idEssai) :int
    {
        $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT idOp
FROM Essai 
WHERE id = :id

SQL
        );
        $result->bindValue(':id', $idEssai);
        $result->execute();
        $result = $result->fetch();
        return (int)$result['idOp'];

    }


}