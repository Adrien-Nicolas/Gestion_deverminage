<?php

declare(strict_types=1);

ini_set('memory_limit', '200100M');

class ConfigGraph
{
    private $id;
    private $seuilBas;
    private $seuilHaut;
    private $nomBleu;
    private $nomRouge;
    private $nomVert;
    private $nomOrange;
    private $nomOrdonneeGauche;
    private $nomOrdonneeDroite;
    private $abscisse;

    /**
     * Constructeur de la classe ConfigGraph
     *
     * @param int $id
     * @param int $seuilBas ;
     * @param int $seuilHaut ;
     * @param string $nomBleu ;
     * @param string $nomRouge ;
     * @param string $nomVert ;
     * @param string $nomOrange ;
     * @param string $nomOrdonneeGauche ;
     * @param string $nomOrdonneeDroite ;
     *  @param string $abscisse ;
     */


    public function __construct(int $id, int $seuilBas, int $seuilHaut, string $nomBleu, string $nomRouge, string $nomVert, string $nomOrange, string  $nomOrdonneeGauche, string  $nomOrdonneeDroite, string $abscisse)
    {
        $this->id = $id;
        $this->seuilBas = $seuilBas;
        $this->seuilHaut = $seuilHaut;
        $this->nomBleu = $nomBleu;
        $this->nomRouge = $nomRouge;
        $this->nomVert = $nomVert;
        $this->nomOrange = $nomOrange;
        $this->nomOrdonneeGauche = $nomOrdonneeGauche;
        $this->nomOrdonneeDroite = $nomOrdonneeDroite;
        $this->abscisse = $abscisse;

    }


    /**
     * Méthode de la classe ConfigGraph qui permet la création d'une config avec son ID
     *
     * @param int $id
     * @return ConfigGraph
     */

    static function getConfigFromId(int $id): ConfigGraph
    {
        try {
            $result = MyPDO::getInstance()->prepare(<<<SQL
SELECT * 
FROM ConfigGraph
WHERE id = :id;
SQL
            );
        } catch (Exception $e) {
        }
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        return new ConfigGraph($id, (int)$result["seuilBas"], (int)$result["seuilHaut"], $result["nomBleu"], $result["nomRouge"], $result["nomVert"], $result["nomOrange"], $result["nomOrdonneeGauche"],$result["nomOrdonneeDroite"], $result["abscisse"]);
    }



    /**
     * Méthode de la classe ConfigGraph, permet l'obtention de l'ID ConfigGraph
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->seuilBas;

    }

    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du seuil bas
     *
     * @return int
     */
    public function getseuilBas(): int
    {
        return $this->seuilBas;

    }

    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du seuil haut
     *
     * @return int
     */
    public function getseuilHaut(): int
    {
        return $this->seuilHaut;
    }


    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de la courbe bleu
     *
     * @return int
     */
    public function getnomBleu(): string
    {
        return $this->nomBleu;
    }


    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de la courbe rouge
     *
     * @return int
     */
    public function getnomRouge(): string
    {
        return $this->nomRouge;
    }


    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de la courbe verte
     *
     * @return int
     */
    public function getnomVert(): string
    {
        return $this->nomVert;
    }


    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de la courbe orange
     *
     * @return int
     */
    public function getnomOrange(): string
    {
        return $this->nomOrange;
    }

    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de l'ordonnée gauche
     *
     * @return int
     */
    public function getnomOrdonneeGauche(): string
    {
        return $this->nomOrdonneeGauche;
    }

    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de l'ordonnée droite
     *
     * @return int
     */
    public function getnomOrdonneeDroite(): string
    {
        return $this->nomOrdonneeDroite;
    }

    /**
     * Méthode de la classe ConfigGraph, permet l'obtention du nom de l'abscisse
     *
     * @return int
     */
    public function getAbscisse(): string
    {
        return $this->abscisse;
    }


    /**
     * Méthode de la classe ConfigGraph, permet la modification de la table ConfigGraph
     *
     * @param int $id
     * @param string $abscisse
     * @param string $nomBleu
     * @param string $nomOrange
     * @param string $nomOrdonneeDroite
     * @param string $nomOrdonneeGauche
     * @param string $nomRouge
     * @param string $nomVert
     * @param int $seuilBas
     * @param int $seuilHaut
     */
    static function UpdateConfig(int $id, int $seuilBas, int $seuilHaut, string $nomBleu, string $nomRouge,string $nomVert, string $nomOrange, string $nomOrdonneeGauche, string $nomOrdonneeDroite, string $abscisse)
    {
        try {

            $result = MyPDO::getInstance()->prepare(<<<SQL
UPDATE ConfigGraph SET seuilBas = :seuilBas,
        seuilHaut = :seuilHaut,
        nomBleu = :nomBleu,
        nomRouge = :nomRouge,
        nomVert = :nomVert,               
        nomOrange = :nomOrange,
        nomOrdonneeGauche = :nomOrdonneeGauche,
        nomOrdonneeDroite = :nomOrdonneeDroite,
        abscisse = :abscisse
WHERE id = :id 
SQL
            );
            $result->bindValue(':id', $id);
            $result->bindValue(':seuilBas', $seuilBas);
            $result->bindValue(':seuilHaut', $seuilHaut);
            $result->bindValue(':nomBleu', $nomBleu);
            $result->bindValue(':nomRouge', $nomRouge);
            $result->bindValue(':nomVert', $nomVert);
            $result->bindValue(':nomOrange', $nomOrange);
            $result->bindValue(':nomOrdonneeGauche', $nomOrdonneeGauche);
            $result->bindValue(':nomOrdonneeDroite', $nomOrdonneeDroite);
            $result->bindValue(':abscisse', $abscisse);
            $result->execute();
        }catch (Exception $e){
            
        }
    }



}