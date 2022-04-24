<?php

/**
 * Cette function charge automatiquement les fichier .php dans /src
 */
spl_autoload_register(function(string $className)
{
    $class_file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . "$className.php";
    if (file_exists($class_file)) require_once $class_file;
});
