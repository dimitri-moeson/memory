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
         * etablit le chemin du fichier $file_name et l'inclut automatiquement
         * verifie la classe $class_name
         * interromp le traitement en cas d'absence de la classe
         * @param $class_name
         */
        static function call($class_name){

            $file_name = str_replace("\\","/", $class_name);

            if(file_exists(ROOT."/".$file_name.".php"))
                require ROOT."/".$file_name.".php";

            if (class_exists($class_name))
                return true;

            return false ;
        }
    }
}