<?php namespace kernel {

    use PDO;
    use PDOStatement;

    /**
     * Class Executer pour executer et traiter les requetes SQL
     * @package kernel
     */
    class Executer
    {
        /**
         * @var PDO $pdo
         */
        private $pdo;

        /**
         * Executer constructor.
         * @param PDO $pdo
         */
        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo ;
        }

        /**
         * execute une requete simple
         * @param $statement
         * @param $class_name
         * @param bool $one
         * @return array
         */
        public function query( $statement, $class_name = null , $one = false  )
        {
            $req = $this->getPDO()->query($statement);

            $this->fetchMode($req, $class_name);

            return $this->result($req, $one);
        }

        /**
         * execute une requete preparée
         * @param $statement
         * @param $class_name
         * @param bool $one
         * @param array $attrib
         * @param bool $isModif
         * @return array|bool|Entity
         */
        public function prepare($statement, $class_name = null ,  $one = false, $attrib = [] , $isModif = false  )
        {
            $req = $this->getPDO()->prepare($statement);

            $this->bind($req , $attrib ,$isModif ) ;

            $res = $req->execute();

            if($isModif === true ) return $res ;

            $this->fetchMode($req, $class_name);

            return $this->result( $req, $one);
        }

        /**
         * attribue les variable de la requete preparée
         * @param PDOStatement $req
         * @param array $attrib
         * @param bool $isModif
         */
        private function bind( PDOStatement &$req , $attrib = [] , $isModif = false )
        {
            foreach ($attrib as $key => $value)
            {
                if($isModif)
                {
                    if( is_integer($value))
                    {
                        $req->bindValue(":" . $key, "".$value , PDO::PARAM_INT );
                    }
                elseif (is_a($value,'DateTime'))
                    {
                        $req->bindValue(":" . $key, "".$value->format("Y-m-d H:i:s") , PDO::PARAM_STR );
                    }
                else
                    {
                        $req->bindValue(":" . $key, "".$value , PDO::PARAM_STR);
                    }
                }
                else
                {
                    $req->bindParam(":" . $key, $value);
                }
            }

        }

        /**
         * on recupere les resultats sous la class saisie en parametres
         * si pas de class en parametre, on recupere le resultat dans la class parent Entity
         * @param PDOStatement $req
         * @param null $class_name
         */
        private function fetchMode(PDOStatement &$req , $class_name = null )
        {
            if(is_null($class_name))
                $req->setFetchMode( PDO::FETCH_CLASS, Entity::class);
            else
                $req->setFetchMode( PDO::FETCH_CLASS, $class_name);
        }

        /**
         * on recupere le(s) resultat(s) de la requete executé
         * @param PDOStatement $req
         * @param bool $one
         * @return array|Entity
         */
        private function result( PDOStatement $req,  $one = false )
        {
            if($one)
                return $req->fetch();
            else
                return $req->fetchAll();
        }

        /**
         * @return PDO
         */
        private function getPDO(): PDO
        {
            return $this->pdo;
        }
    }
}