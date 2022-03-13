<?php namespace kernel {

    /**
     * Class Controller execute les requetes utilisateurs et affiche les résultat
     * @package kernel
     */
    class Controller
    {
        /**
         * execute le rendu HTML pour la requete de l'utilisateur
         * @param $view
         * @param array $variables
         */
        protected function render($view)
        {
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
         * redirection PHP
         * @param $location
         */
        protected function redirect($location)
        {
            header("Status: 301 Moved Permanently", false, 301);
            header("location:?p=".$location);
            $this->render("404");

        }

        /**
         * raccourci pour le lancement de requete simple
         * @param $statement
         * @param null $class_name
         * @param bool $one
         * @return array
         * @throws \Exception
         */
        protected function DBquery($statement, $class_name = null ,  $one = false)
        {
            return App::getInstance()->getDB()->query_init()->query($statement,$class_name,false  );
        }

        /**
         * raccourci pour le lancement de requete preparée
         * @param $statement
         * @param null $class_name
         * @param bool $one
         * @param array $attrib
         * @return array|bool|Entity
         * @throws \Exception
         */
        protected function DBprepare($statement, $class_name = null ,  $one = false, $attrib = [])
        {
            return App::getInstance()->getDB()->query_init()->prepare($statement,$class_name,false , $attrib,false  );
        }

        /**
         * gestion des erreurs 404
         * @param $view
         */
        protected function notfound($view){

            header("HTTP/1.1 404 Not Found");
            $this->render("404");
        }

        /**
         * execute la commande de l'utilisateur
         * appel du controller et de l'action
         * puis generation et affichage du rendu HTML
         * @param $p
         */
        public static function execute($p)
        {
            $request = explode(".",$p);

            if(count($request) === 2 )
            {
                list($cnt_name, $action) = $request;
            }
            elseif(count($request) === 1 )
            {
                $cnt_name = 'default';
                $action = $request[0];
            }

            $controller_name = "app\controller\\".ucfirst($cnt_name)."Controller";

            /**
             * @var Controller $controller
             */
            $controller = new $controller_name();

            $call = array($controller,$action);

            /**
             * on verifie que le controller->action est fonctionnelle
             */
            if(is_callable($call))
            {
                /**
                 * execution finale des scripts
                 */
                call_user_func($call);

                $controller->render($action);
            }
            else
            {
                $controller->notfound($action);
            }

            die();
        }
    }
}