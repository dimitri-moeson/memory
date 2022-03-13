<?php namespace kernel {

    /**
     * Class Request
     * @package kernel
     */
    class Request
    {
        /**
         * @var Request $_instance
         */
        private static $_instance;

        /**
         * @var string $racine
         */
        private $racine;

        /**
         * @var string $request
         */
        private $request;

        /**
         * @var $cnt_name
         */
        private $cnt_name;

        /**
         * @var
         */
        private $action;

        /**
         * Instance unique de Request
         * @return Request
         */
        public static function getInstance():Request
        {
            if(is_null(self::$_instance)){
                self::$_instance = new Request();
            }
            return self::$_instance;
        }

        /**
         * Request constructor.
         */
        private function __construct()
        {
            $protocol = $this->protocol();

            $racine =  str_replace("index.php","", getenv("SCRIPT_NAME"));

            $this->racine = $protocol.getenv('SERVER_NAME').$racine ;

            if($racine !== "/")
            {
                $this->request = str_replace($racine,"", getenv("REQUEST_URI"));
            }
            else
            {
                $this->request =  getenv("REQUEST_URI");
            }
        }


        /**
         * verifie le protocol du site
         * @return string
         */
        private function protocol(){

            if(stripos(getenv('SERVER_PROTOCOL'),'https') === 0)
            {
                return "https://";
            }

            if(getenv('HTTP_X_FORWARDED_PROTO') === 'https')
            {
                return "https://";
            }

            if(getenv('SERVER_PORT') === 443)
            {
                return "https://";
            }

            if(getenv('HTTPS') === 'on')
            {
                return "https://";
            }

            if(getenv('REQUEST_SCHEME')!== false )
            {
                return getenv('REQUEST_SCHEME')."://";
            }

            return "http://";
        }

        /**
         * construit l'url fourni en parametres
         * @param array $path
         * @param bool $file
         * @return string URL
         * @throws \Exception
         */
        public static function url( $path = [] , $file = false ):string
        {
            $rewrite = App::getInstance()->param("active","rewrite") == 1 ? true : false ;

            if($rewrite || $file )
                return self::getInstance()->getRacine().implode("/",$path);

            return self::getInstance()->getRacine()."?p=".implode(".",$path);
        }

        /**
         * capture la requete utilisateur et construit la demande
         */
        public function dispatch()
        {
            if(GlobalData::getInstance()->exists("p"))
            {
                $req = explode(".", GlobalData::getInstance()->get("p", "index" ));
            }
            else
            {
                $req = explode("/", $this->request);
            }

            $req = array_values(array_filter($req));

            if(count($req) === 1 )
            {
                $request = array( 'default', $req[0] );
            }
            else
            {
                $request = $req;
            }

            list( $this->cnt_name , $this->action ) = Router::getInstance()->getRoute($request);


        }

        /**
         * @return string
         */
        public function getRequest(): string
        {
            return $this->request;
        }

        /**
         * @return string
         */
        public function getRacine(): string
        {
            return $this->racine;
        }

        /**
         * Si le controller existe, retourne le chemin
         * sinon, le controller par defaut
         * @return string
         */
        public function getCntName():string
        {
            $classname = "app\controller\\".ucfirst($this->cnt_name)."Controller";

            if(class_exists($classname))
                return $classname;

            return "app\controller\DefaultController";
        }

        /**
         * si l'action exste dans le controller, retourne l'action
         * sinon, retoure 404 - not found
         * @return string
         */
        public function getAction():string
        {
            $class = $this->getCntName();

            if(method_exists($class,$this->action))
                return $this->action;

            return "404";
        }
    }
}