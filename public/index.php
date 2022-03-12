<?php

use kernel\App;
use kernel\GlobalData;

/**
 * Racine : chemin des fichiers par defaut
 * __DIR__ dossier courant
 * dirname() dossier parent
 * realpath() mise au propre du chemin
 */
define('ROOT' , realpath(dirname(__DIR__)));

require ROOT."/kernel/App.php";

/**
 * autoloading des class
 * connexion à la base de données
 * ...
 */
App::getInstance()->load();

/**
 * recuperation de la requete utilisateur
 */
$p = GlobalData::getInstance()->get('p','index');

/**
 * execution du controller principale
 */
\kernel\Controller::execute($p);



?>