<?php namespace kernel {

    /**
     * Class GlobalData recupere les infos de formulaire ($_POST) ou d'URL ($_GET)
     * @package kernel
     */
    class GlobalData
    {
        /**
         * @var GlobalData $_instance
         */
        private static $_instance ;

        /**
         * @var string $method POST/GET
         */
        private $method;

        /**
         * @return GlobalData
         */
        public static function getInstance() :GlobalData
        {
            if(is_null(self::$_instance)){
                self::$_instance = new GlobalData();
            }
            return self::$_instance;
        }

        /**
         * GlobalData constructor.
         */
        private function __construct()
        {
            $this->method = getenv('REQUEST_METHOD');
        }

        /**
         * verifie si un formulaire a été envoyé
         * @return bool
         */
        public function submitted() :bool
        {
            if($this->method === 'POST')
                if(!empty($_POST))
                    return true ;

            return false ;
        }

        /**
         * retourne l'integralité de la requete utilisateur
         * @return mixed
         */
        public function content() :array
        {
            if($this->method === 'POST')
                return $_POST;

            return $_REQUEST ;
        }

        /**
         * verifie l'existence d'une variable
         * @param $key
         * @return bool
         */
        public function exists($key):bool
        {
            if($this->method === 'POST')
                if( isset($_POST[$key]) )
                    return array_key_exists($key, $_POST) ;

            if (isset($_GET[$key]))
                return array_key_exists($key, $_GET);

            if (isset($_REQUEST[$key]))
                return array_key_exists($key, $_REQUEST);

            return false;
        }

        /**
         * recupere une variable si elle existe,
         * sinon, retourne la valeur par defaut
         * @param $key
         * @param null $default
         * @return null
         */
        public function get($key , $default = null ) : ?string
        {
            if($this->method === 'POST')
                if(array_key_exists($key, $_POST))
                    return $_POST[$key];

            if(array_key_exists($key, $_GET))
                return $_GET[$key];

            if(array_key_exists($key, $_REQUEST))
                return $_REQUEST[$key];

            return $default ;
        }

        /**
         * modifie ou crée une variable
         * @param $key
         * @param $value
         * return bool
         */
        public function set($key, $value) :bool
        {
            if($this->method === 'POST')
            {
                $_POST[$key] = $value ;
                return true ;
            }

            $_REQUEST[$key] = $value ;
            return true ;
        }

        /**
         * supprime une variable
         * @param $key
         * @return bool
         */
        public function delete($key) :bool
        {
            if ($this->exists($key))
            {
                if($this->method === 'POST')
                {
                    unset($_POST[$key]) ;
                    return true ;
                }

                unset($_REQUEST[$key]);
                return true ;
            }

            return false ;
        }
    }
}