<?php declare(strict_types=1);

/**
 * Classe utilitaire portant un générateur aléatoire de chaînes
 */
class Random
{
    /**
     * Production d'un code aléatoire (minuscule, majuscule et chiffre)
     *
     * @param int $size taille de la chaîne
     *
     * @return string chaîne aléatoire
     *
     * @see rand() https://www.php.net/manual/fr/function.rand.php
     * @see ord() https://www.php.net/manual/fr/function.ord.php
     * @see chr() https://www.php.net/manual/fr/function.chr.php
     */
    public static function string(int $size)
    {
        $string = '';
        for ($i = 0; $i < $size; $i++) {
            // Une chance sur trois
            switch (rand(0, 2)) {
                case 0 : // Majuscule
                    $string .= chr(rand(ord('A'), ord('Z')));
                    break;
                case 1 : // Minuscule
                    $string .= chr(rand(ord('a'), ord('z')));
                    break;
                case 2 : // Chiffre
                    $string .= chr(rand(ord('1'), ord('9')));
                    break;
            }
        }
        return $string;
    }
}
