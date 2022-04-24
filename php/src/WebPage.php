<?php

class WebPage
{
    protected $head; #string
    protected $title; #string
    protected $body; #string
    protected $minHeight; #int
    protected $admin; #bool
    protected $login; #bool

    /**
     * Constructeur de la classe WebPage, prend en parametre le titre de la page web
     *
     * @param title titre
     */

    public function __construct (string $title=null)
    {
        $this->head = "";
        $this->title = $title;
        $this->body = "";
        $this->minHeight = 0;
        $this->admin = false;
        $this->login = false;
    }


    /**
     * Accesseur de la classe WebPage, retourne le body
     *
     * @return body
     */

    public function body(): string{
        return $this->body;
    }

    /**
     * Accesseur de la classe WebPage, retourne le head
     *
     * @return head
     */

    public function head(): string{
        return $this->head;
    }

    /**
     * modificateur de la classe WebPage, change le titre
     *
     * @param title
     */

    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * modificateur de la classe WebPage, ajoute une hauteur minimum
     *
     * @param int $minHeight
     */

    public function setMinHeight(int $minHeight) {
        $this->minHeight = $minHeight;
    }

    /**
     * Accesseur de la classe WebPage sur l'attribut admin
     *
     * @return false
     */
    public function getAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * modificateur de la classe WebPage, met admin sur true
     *
     * @param false $admin
     */
    public function setAdmin(): void
    {
        $this->admin = true;
    }

    /**
     * modificateur de la classe WebPage, met login sur true
     *
     * @param false $login
     */
    public function setLogin(): void
    {
        $this->login = true;
    }

    /**
     * modificateur de la classe WebPage, ajoute au head
     *
     * @param content
     */

    public function appendToHead(string $content){
        $this->head.=$content;
    }

    /**
     * modificateur de la classe WebPage, ajoute du CSS
     *
     * @param css
     */

    public function appendCss(string $css){
        $this->head.='<style>'.$css.'</style>';
    }

    /**
     * modificateur de la classe WebPage, ajoute un lien vers du CSS dans le head
     *
     * @param url
     */

    public function appendCssUrl(string $url):void{
        $this->head.='<link href='.$url.' rel="stylesheet" type="text/css">';
    }

    /**
     * modificateur de la classe WebPage, ajoute du Js
     *
     * @param js
     */

    public function appendJs(string $js) {
        $this->appendContent("<script>".$js."</script>");
    }



    /**
     * modificateur de la classe WebPage, ajoute un lien vers du Js dans le head
     *
     * @param url
     */

    public function appendJsUrl(string $url) {
        $this->appendToHead("<script src=".$url."></script>");
    }

    /**
     * modificateur de la classe WebPage, ajoute du contenu dans le body
     *
     * @param content
     */

    public function appendContent(string $content) {
        $this->body.=$content;
    }

    /**
     * Produire la page Web complète.
     *
     * @return string
     */

    public function toHTML() {

        $authentication = new SecureUserAuthentication();
        
        if ($authentication->isUserConnected()){
            $user = $authentication->getUserFromSession();
            $pseudo = $user->getNickname();
            if ($user->getRole() == 'Administrateur') {
                $this->setAdmin();
            }
        }
        $html = <<<HTML
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/logo-page.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="manifest" href="manifest.json" crossorigin="use-credentials">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    
HTML;
        $html.= '<title>'.$this->title.'</title>'.$this->head();
        $html.= $this->head.'</head>';
        $this->appendContent(<<<HTML
        <div class="nav-container">
        <a class="name-nav" href="index.php">
        <div class="img-nav"></div>
            <!--<img src="img/logo.png" alt="logo epl" class="logo-nav">-->
            <p>Administration déverminage</p>
        </a>
HTML);
        if(!$this->login) {
            $this->appendContent(<<<HTML
            <nav>
                <div class="nav-mobile"><a id="nav-toggle" href="#!"><span></span></a></div>
                <ul class="nav-list">
                    <li>
                        <div class="search">
                            <form id="searchform" action="search.php" method="GET">
                                <input placeholder="Rechercher" name="search">
                            </form>
                            <button class="searchform" type="submit" form="searchform" value="Submit">
                                <span
                                    class="iconify" data-icon="si-glyph:magnifier" data-inline="false"
                                    style="color: black;" data-width="20px" data-height="20px">
                                </span>
                            </button>
                        </div>
                    </li>
HTML
            );
            if ($this->admin) {
                $this->appendContent(<<<HTML
                    <li>
                        <a class="unselectable cpointer">Admin</a>
                        <ul class="nav-dropdown">
                            <li>
                                <a class="unselectable" href="createuser.php">Créer un compte</a>
                            </li>
                            <li>
                                <a class="unselectable" href="users.php">Gérer les comptes</a>
                            </li>
                            <li>
                                <a class="unselectable" href="stats.php">Voir les statistiques</a>
                            </li>
                            <li>
                                <a class="unselectable" href="product.php">Gérer les produits</a>
                            </li>
                        </ul>
                    </li> 
                    HTML
                );
            }
            $this->appendContent(<<<HTML
                    <script>
                        (function ($) {
                            $(function () {
                                $('nav ul li a:not(:only-child)').click(function (e) {
                                    $(this).siblings('.nav-dropdown').toggle();
                                    $('.nav-dropdown').not($(this).siblings()).hide();
                                    e.stopPropagation();
                                });
                                $('html').click(function () {
                                    $('.nav-dropdown').hide();
                                });
                                $('#nav-toggle').click(function () {
                                    $('nav ul').slideToggle();
                                });
                                $('#nav-toggle').on('click', function () {
                                    this.classList.toggle('active');
                                });
                            });
                        })(jQuery);
                    </script>
                    <li>
                        <a class="unselectable" href="php/logout.php">Se déconnecter ($pseudo)</a>
                    </li>
                </ul>
            </nav>
        HTML
            );
        }
        $this->appendContent(<<<HTML
    </div>
HTML);

        $html.= '<div class="bg-white">'.$this->body()." </div>";
        $html.= <<<HTML
<footer>
    <span class="img-footer">
        <div class="img-nav"></div>
        &copy;2021&nbsp;
        <a href="https://eplconcept.com" class="ahref">eplconcept.com</a>
    </span>
    <div>
        <span>EPL SARL</span>
        <span>SARL au capital de 300.000 €</span>
        <span>RCS de Meaux 41288818200059</span>
        <span>APE 2612Z</span>
    </div>
    <div>
        <span>EPL</span>
        <span>4 impasse des Saules</span>
        <span>77515 Faremoutiers</span>
        <span>epl@eplconcept.com</span>
        <span>+33 (0)1 64 65 11 73</span>
    </div>
</footer>
HTML;
        $html.='</body></html>';
        return $html;
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web.
     *
     * @param string La chaîne à protéger
     */

    public static function escapeString(string $string) : string
    {
        return htmlentities($string, ENT_HTML5 | ENT_QUOTES);
    }

    /**
     * Méthode de la classe WebPage, Ajoute un auteur
     *
     * @param string $name
     */
    public function addAuthor(string $name){
        $rep = '<meta name="author" content="'.$name.'">';
        $this->appendToHead($rep);
    }

}
