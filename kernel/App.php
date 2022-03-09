<?php
namespace kernel {

    /**
     * Class App
     * @package kernel
     */
    class App
    {
        /**
         * @var App $_instance
         */
        private static $_instance ;

        /**
         * @var Database $db
         */
        private $db ;

        /**
         * chargement des composant principaux & initialisation des variables
         * - Autoloader
         * - Base de données
         */
        public function load(){

            require 'Autoloader.php';

            \kernel\Autoloader::register();

            $this->getDB();

        }

        /**
         * Instance unique de App
         * @return App
         */
        public static function getInstance()
        {
            if(is_null(self::$_instance)){
                self::$_instance = new App();
            }
            return self::$_instance;

        }

        /**
         * Instance unique de Database
         * @return \kernel\Database
         * @throws \Exception
         */
        public function getDB()
        {
            if(is_null($this->db)){

                $config = Config::getInstance(ROOT."/config/db.ini");

                $this->db = new Database(

                    $config->getDB("db_name"),
                    $config->getDB("db_user"),
                    $config->getDB("db_pass"),
                    $config->getDB("db_host"),
                    $config->getDB("db_serv"),
                    $config->getDB("db_char")

                );
            }
            return $this->db;
        }
    }
}