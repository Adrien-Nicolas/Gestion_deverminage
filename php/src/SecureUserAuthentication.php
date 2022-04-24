<?php
declare(strict_types=1);

require_once "Session.php";
require_once "NotLoggedInException.php";

class SecureUserAuthentication
{
    public const SESSION_KEY = '__UserAuthentication__';
    public const SESSION_USER_KEY = 'user';

    const CODE_INPUT_NAME = 'code';
    const SESSION_CHALLENGE_KEY = 'challenge';
    const RANDOM_STRING_SIZE = 16;

    private $user = null;

    /**
     * Constructeur
     *
     * @throws SessionException si la session ne peut pas être démarrée
     */
    public function __construct()
    {
        try{
            $this->user = $this->getUserFromSession();
        }catch(NotLoggedInException $exception) {}
    }



    /**
     * Lecture de l'objet Utilisateur dans la session
     *
     * @return Utilisateur
     *
     * @throws SessionException si la session ne peut pas être démarrée
     * @throws NotLoggedInException si l'objet n'est pas dans la session
     */
    public function getUserFromSession():Utilisateur
    {
        Session::start();
        if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])) {
            $User = $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
            if ($User instanceof Utilisateur) {
                $this->setUser($User);
                return $User;
            }
        }
        throw new NotLoggedInException();
    }


    /**
     * Affecte le user passé en paramètre à la propriété $User et le mémorise dans les données de session
     * @param Utilisateur $User
     * @throws SessionException si la session ne peut pas être démarrée
     */
    public function setUser(Utilisateur $User)
    {
        $this->user = $User;
        Session::start();
        $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = $this->user;
    }


    /**
     * Accesseur à l'Utilisateur connecté
     *
     * @return Utilisateur Utlisateur connecté
     * @throws NotLoggedInException Si aucun utilisateur n'est connecté
     */
    public function getUser(): Utilisateur
    {
        if (!isset($this->user)) {
            throw new NotLoggedInException("Aucun utilisateur connecté");
        }

        return $this->user;
    }

    /**
     * Test si un utilisateur est mémorisé dans les données de session
     * @return bool un utilisateur est connecté
     * @throws SessionException si la session ne peut pas être démarrée
     */
    public function isUserConnected(): bool
    {
        Session::start();
        return isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])
            && $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof Utilisateur;
    }


    /**
     * Production d'un formulaire de connexion contenant
     * un challenge et une méthode JavaScript de hachage
     * @param string $action URL cible du formulaire
     * @param string $submitText texte du bouton d'envoi
     *
     * @return string code HTML du formulaire
     * @throws SessionException si la session ne peut pas être démarrée
     */
    public function loginForm(string $action): string
    {
        $codeInputName = self::CODE_INPUT_NAME;

        // Mise en place de la session
        Session::start();
        // Mémorisation d'un challenge dans la session
        $_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY] = $challenge = Random::string(self::RANDOM_STRING_SIZE);
        // Le formulaire avec le code JavaScript permettant le hachage SHA512
        // Le retour attendu par le serveur est SHA2(SHA2(pass, 512)
        //                                           +challenge
        //                                           +SHA2(login, 512),
        //                                           512),
        // c'est-à-dire CryptoJS.SHA512(CryptoJS.SHA512(pass)
        //                              +challenge
        //                              +CryptoJS.SHA512(login))
        // en JavaScript avec la bibliothèque CryptoJS.
        $html = "";
        $cerreur ="";
        if (isset($_GET["erreur"])){
            $cerreur = $_GET["erreur"];
        }

        $html .=<<<HTML

<form name='auth' action='{$action}' id='login_form' method='POST' autocomplete='off' class="form-visible">
    <h1>Se connecter</h1>
    <span class="error">$cerreur</span>
    <label>Entrez votre nom d'utilisateur :</label>
    <input type="login" id="login_input" placeholder="Votre nom d'utilisateur" required>
    <label>Entrez votre mot de passe :</label>
    <input type="password" placeholder="Votre mot de passe" id="password_input" required>
    <div class="fpass">
        <div>
            <input type="checkbox" onclick="showPassword()" id="showpsd"><label for="showpsd">Afficher le mot de passe</label>
        </div>       
    </div>
    <input type='hidden' id="challenge_input" value='{$challenge}'>
    <input type='hidden' id="code_input" name='{$codeInputName}'>
    <div class="btn-login">
        <input type="submit" value="Se connecter">
    </div>
</form>
<script type='text/javascript' src='../js/sha512.js'></script>
<script type='text/javascript'>
document.getElementById('login_form').onsubmit = function () {
    let login = document.getElementById('login_input');
    let password = document.getElementById('password_input');
    let challenge = document.getElementById('challenge_input');
    let code = document.getElementById('code_input');
console.log(login.value, password.value, challenge.value, code.value);
    if (login.value.length && password.value.length) {
        code.value = CryptoJS.SHA512(CryptoJS.SHA512(password.value).toString()
                                       +challenge.value
                                       +CryptoJS.SHA512(login.value).toString()).toString();
console.log(login.value, password.value, challenge.value, code.value);
        return true;
    }
    return false;
}
</script>
HTML;
        return $html;
    }

    /**
     * Validation de la connexion de l'Utilisateur
     *
     * @return Utilisateur User authentifié
     *
     * @throws AuthenticationException si l'authentification échoue
     */
    public function getUserFromAuth(): Utilisateur
    {
        try { // Capturer toutes les exceptions, effacer le challenge et les renvoyer
            if (!isset($_POST[self::CODE_INPUT_NAME])) {
                throw new AuthenticationException('pas de login/pass fournis');
            }

            Session::start();
            if (!isset($_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY])) {
                throw new AuthenticationException('problème de challenge');
            }

            // Préparation de la requête
            $stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT id
    FROM Utilisateur
    WHERE SHA2(CONCAT(password, CONCAT(:challenge, SHA2(nickname, 512))), 512) = :code
SQL
            );
            $stmt->execute(
                [
                    ':code' => $_POST[self::CODE_INPUT_NAME],
                    ':challenge' => $_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY],
                ]
            );
            $user_data = $stmt->fetch();
            // Test de réussite de la sélection
            if ($user_data !== false) {
                $user = Utilisateur::getUtilisateurFromId((int)$user_data['id']);
                $this->setUser($user);
                return $user;
            } else {
                header('Location:login.php?erreur=Votre email ou mot de passe est incorect');
            }
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (Exception | SessionException | PDOException $e) {
            throw new AuthenticationException("Problème technique {$e->getMessage()}");
        } finally { // Dans tous les cas
            /*
             * Effacement du challenge après toute tentative d'authentification.
             * Toute la sécurité repose sur lui,
             * il doit rester valide le moins longtemps possible.
             */
            if (isset($_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY])) {
                unset($_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY]);
            }
        }
    }

}

