<?php namespace kernel {

    use ReflectionClass;
    use ReflectionMethod;

    /**
     * Class Router
     * @package kernel
     */
    class Router
    {
        /**
         * @var Router $_instance
         */
        private static $_instance;

        /**
         * Instance unique de Request
         * @return Request
         */
        public static function getInstance():Router
        {
            if(is_null(self::$_instance))
            {
                self::$_instance = new Router();
            }
            return self::$_instance;
        }

        /**
         * extrait le pattern de route dans le commentaire
         *
         * @param ReflectionClass|ReflectionMethod $obj
         * @return bool|mixed
         */
        private function pattern_route($obj):string
        {
            $Comment = $obj->getDocComment();

            if ($Comment !== false)
            {
                preg_match('/@Route\\(\'(.*)\'\\)/', $Comment, $arrayMatches);

                if (isset($arrayMatches[1]))
                {
                    return str_replace("/", "", $arrayMatches[1]);
                }
            }
            return false;
        }

        /**
         * check si la class correspond au cnt en request
         *
         * @param ReflectionClass $reflectionClass
         * @param $cnt
         * @return bool
         */
        private function analys_class(ReflectionClass $reflectionClass, $cnt ) :bool
        {
            if ($reflectionClass !== false )
            {
                $routeClass = $this->pattern_route($reflectionClass);

                $NameClass = strtolower(str_replace("Controller", "", $reflectionClass->getShortName() ));

                if ( $routeClass === $cnt || $NameClass === $cnt)
                {
                    return true;
                }

            }

            return false ;
        }

        /**
         * check si la method correspond au act en request
         *
         * @param ReflectionMethod $reflectionMethod
         * @param $act
         * @return bool
         */
        private function analys_method(ReflectionMethod $reflectionMethod , $act ):bool
        {
            if ($reflectionMethod !== false)
            {
                $routeUri = $this->pattern_route($reflectionMethod);
                $nameUri = $reflectionMethod->getName();

                if ($routeUri === $act || $nameUri === $act)
                {
                    return true;
                }

            }
            return false ;
        }

        /**
         * list les controller dans le repertoire
         *
         * @return array
         * @throws \Exception
         */
        private function list_cnt():array
        {
            $arrayGlob = scandir(realpath(ROOT. '/app/controller/'));

            if (empty($arrayGlob)) {
                 throw new \Exception('No route to load');
            }

            $cnts = [];

            foreach ($arrayGlob as $filepath)
            {
                if(strpos($filepath, "Controller") !== false)
                {
                    $cnts[] = str_replace(".php", "", $filepath);
                }
            }

            return $cnts ;
        }

        /**
         * listes des Methodes dans un Controller
         *
         * @param ReflectionClass $reflectionClass
         * @return array $arrayReflectionMethods
         */
        private function list_cmd( ReflectionClass $reflectionClass):array
        {
            $arrayReflectionMethods = $reflectionClass->getMethods();

            if ($arrayReflectionMethods !== false)
            {
                return $arrayReflectionMethods;
            }

            return [];
        }

        /**
         * controle la requete utilisateur et retoure le controller - action correspondant
         *
         * @param $request
         * @return array
         * @throws \Exception
         */
        public function getRoute($request):array
        {
            foreach ($this->list_cnt() as $objectName)
            {
                try
                {
                    /**
                     * @var Reflection : faire une introspection sur des classes, méthodes et fonctions
                     * ou encore de récupérer des blocs de commentaires.
                     */
                    $reflectionClass = new ReflectionClass('app\controller\\' . $objectName);

                    if($this->analys_class($reflectionClass, $request[0]))
                    {
                        foreach ($this->list_cmd($reflectionClass) as $reflectionMethod)
                        {
                            if ($this->analys_method($reflectionMethod, $request[1]))
                            {
                                $methodName = $reflectionMethod->getName();

                                return [$objectName, $methodName];
                            }
                        }
                    }
                }
                catch (\ReflectionException $e)
                {
                    echo $e->getMessage();
                }
            }
            return ["default","404"] ;
        }
    }
}