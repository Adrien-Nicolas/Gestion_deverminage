<?php

declare(strict_types = 1);

/**
 * Classe permettant de retourner une instance unique de PDO
 * afin de ne pas multiplier les connexions au serveur de base de données.
 */
final class MyPDO
{
    /**
     * instance unique de PDO.
     *
     * @var PDO $PDOInstance
     */
    private static $PDOInstance = null;

    /**
     *  DSN pour la connexion BD.
     *
     * @var string $DSN
     */
    private static $DSN = null;

    /**
     * nom d'utilisateur pour la connexion BD.
     *
     * @var string $username
     */
    private static $username = null;

    /**
     * mot de passe pour la connexion BD.
     *
     * @var string $password
     */
    private static $password = null;

    /**
     * options du pilote BD.
     *
     * @var array $driverOptions
     */
    private static $driverOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    /**
     * Constructeur privé
     * (juste pour vous passer l'envie d'instancier des objets MyPDO).
     */
    private function __construct() {}

    /**
     * Point d'accès à l'instance unique.
     * L'instance est créée au premier appel
     * et réutilisée aux appels suivants.
     *
     * @throws Exception si la configuration n'a pas été effectuée
     *
     * @return PDO instance unique
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$PDOInstance)) {
            if (self::hasConfiguration()) {
                self::$PDOInstance = new PDO(self::$DSN, self::$username, self::$password, self::$driverOptions);
            } else {
                throw new Exception(__CLASS__.': Configuration not set');
            }
        }

        return self::$PDOInstance;
    }

    /**
     * Fixer la configuration de la connexion à la BD.
     *
     * @param string $dsn            DNS pour la connexion BD
     * @param string $username       utilisateur pour la connexion BD
     * @param string $password       mot de passe pour la connexion BD
     * @param array  $driver_options options du pilote BD
     */
    public static function setConfiguration(
        string $dsn,
        string $username = '',
        string $password = '',
        array $driver_options = []
    ): void {
        self::$DSN = $dsn;
        self::$username = $username;
        self::$password = $password;
        self::$driverOptions = $driver_options + self::$driverOptions;
    }

    /**
     * Vérifier si la configuration de la connexion à la BD a été effectuée.
     *
     * @return bool
     */
    private static function hasConfiguration(): bool
    {
        return self::$DSN !== null;
    }

    public static function getAllFromTable(string $table) : array
    {
        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT * FROM $table;
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return $array;
    }

    public static function getAllFromTableWithJoinAndID(string $table1, string $table2, string $column, string $columnID, int $id) : array
    {
        $variableID = "t.".$columnID;
        $variable1 = "t.".$column;
        $variable2 = "t2.".$column;

        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT * 
			FROM $table1 t, $table2 t2
			WHERE $variableID = $id AND $variable1 = $variable2;
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return $array;
    }

    public static function getAllFromTableWithID(string $table, string $column, int $id) : array
    {
        $variable = "t.".$column;

        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT * 
			FROM $table t
			WHERE $variable = $id;
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return $array;
    }

    public static function getAllFromTableWithContains(string $table, string $column, string $contains) : array
    {
        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT * FROM $table
			WHERE $column LIKE '%$contains%';
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return $array;
    }

    public static function getMax(string $table) : int
    {
        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT MAX(*) AS "MAX" FROM $table;
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return intval($array[0]["MAX"]);
    }

    public static function getCountAll(string $table) : int
    {
        $statement = MyPDO::getInstance()->prepare(<<<SQL
			SELECT COUNT(*) AS "COUNT" FROM $table;
		SQL);

        $statement->execute();
        $array = $statement->fetchAll();

        return intval($array[0]["COUNT"]);
    }

    public static function removeFromTableWithID(string $table, string $column, int $id)
    {
        $statement = MyPDO::getInstance()->prepare(<<<SQL
			DELETE 
			FROM $table
			WHERE $column = $id;
		SQL);

        $statement->execute();
    }

}
