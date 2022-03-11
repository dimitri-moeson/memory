<?php

use kernel\App;
use kernel\GlobalData;

/**
 * chemin des fichiers par defaut
 */
define('ROOT' , realpath(dirname(__DIR__)));

require ROOT."/kernel/App.php";

/**
 * autoloading des class
 */
App::getInstance()->load();

/**
 * recuperation de la requete utilisateur
 */
$p = GlobalData::getInstance()->get('p','default.index');

/**
 * execution du controller principale
 */
\kernel\Controller::execute($p);



?>