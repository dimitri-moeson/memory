<?php namespace kernel {

    use Exception;

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
         * @var array $settings du fichier .ini
         */
        private $settings = array();

        /**
         * chargement des composant principaux & initialisation des variables
         * - Autoloader
         * - Base de données
         * - etc...
         * @throws Exception
         */
        public function load()
        {
            require 'Autoloader.php';

            \kernel\Autoloader::register();

            try {
                /**
                 * appel de la configuration de la BDD
                 */
                $this->getDB();

            } catch (Exception $e) {

                var_dump($e);

            }
        }

        /**
         * Instance unique de App
         * @return App
         */
        public static function getInstance():App
        {
            if(is_null(self::$_instance)){
                self::$_instance = new App();
            }
            return self::$_instance;
        }

        /**
         * App constructor.
         * importe e fichier de config
         * @param $file
         */
        private function __construct()
        {
            $this->settings = parse_ini_file( ROOT."/config/db.ini", true);
        }

        /**
         * Instance unique de Database
         * @return \kernel\Database
         * @throws Exception
         */
        public function getDB():Database
        {
            if(is_null($this->db))
            {
                $this->db = new Database(

                    $this->param("db_name"),
                    $this->param("db_user"),
                    $this->param("db_pass"),
                    $this->param("db_host"),
                    $this->param("db_serv"),
                    $this->param("db_char")

                );
            }
            return $this->db;
        }

        /**
         * @param $key
         * @param $dom
         * @return mixed
         * @throws Exception
         */
        public function param( $key, $dom = "database" ):string
        {
            $hasBase = array_key_exists($dom, $this->settings);
            $hasKeys = array_key_exists($key, $this->settings[$dom]);

            if (!$hasBase || !$hasKeys ) {
                throw new Exception("Missing config [$dom/$key] informations");
            }

            return $this->settings[$dom][$key];
        }
    }
}