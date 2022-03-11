<?php namespace kernel {
    /**
     * Class Autoloader
     * chargement dynamique des class lors de l'appel
     * @package kernel
     */
    class Autoloader
    {
        /**
         * enregistre la fonction Autoloader::call() comme fonction de chargement par défaut
         */
        static function register() {

            /**
             * __CLASS__ constante de la classe courante
             */
            spl_autoload_register(array(__CLASS__,"call"));

        }

        /**
         * etablit le chemin du fichier $class_name et l'inclut automatiquement
         * @param $class_name
         */
        static function call($class_name){

            $class_name = str_replace("\\","/", $class_name);

            if(file_exists(ROOT."/".$class_name.".php"))
                require ROOT."/".$class_name.".php";
        }
    }
}