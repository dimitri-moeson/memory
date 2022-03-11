<?php
namespace kernel {

    /**
     * Class Controller
     * @package kernel
     */
    class Controller
    {
        /**
         * @param $view
         * @param array $variables
         */
        protected function render($view){

            /**
             * on execute et on stocke la requete dans la variable $content
             */
            ob_start();

            /**
             * on extrait/decompile les donnees
             */
            extract(get_object_vars($this));

            require ROOT."/app/view/".$view.".php";
            /**
             * on ferme le stockage et on recupere le contenu
             */
            $content = ob_get_clean();

            /**
             * $content est affiché dans le template HTML
             */
            require ROOT."/app/view/template/default.php";

            /**
             * on bloque tout une fois le résultat final obtenu
             */
            die();
        }

        /**
         * @param $p
         */
        public static function execute($p){

            list($cnt_name,$action) = explode(".",$p);

            $controller_name = "\\app\\Controller\\".ucfirst($cnt_name)."Controller";

            $controller = new $controller_name();

            $controller->$action();

            $controller->render($action);
        }
    }
}