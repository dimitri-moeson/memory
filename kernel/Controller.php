<?php namespace kernel;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/03/2022
 * Time: 11:44
 */

class Controller
{
    /**
     * @param $view
     * @param array $variables
     */
    protected function render($view , $variables = []){

        /**
         * on execute et on stocke la requete dans la variable $content
         */
        ob_start();

        /**
         * on extrait/decompile les donnees
         */
        extract($variables);

        require ROOT."/app/view/".$view.".php";
        /**
         * on ferme le stockage et o recupere le contenu
         */
        $content = ob_get_clean();

        /**
         * $content est affiché dans le template HTML
         */
        require ROOT."/app/view/template/default.php";

    }
}