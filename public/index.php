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

$p = GlobalData::getInstance()->get('p','home');
/**
 * controller principale
 */
$controller = new \app\controller\DefaultController();

    if($p === "game")   $controller->game();
elseif($p === "ladder") $controller->ladder();
else                    $controller->index();

/**
 * on bloque tout une fois le résultat final obtenu
 */
die();

?>