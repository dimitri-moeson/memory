<?php namespace kernel {

    /**
     * Class Fluent Builder - construit les requetes SQL à partir de la class modele en parametre
     * @package kernel
     */
    class Builder
    {
        private $fields = array();
        private $sources = array();
        private $conditions = array();
        private $ordres = array();
        private $groups = array();

        private $limit;
        private static $classname;

        /**
         * Builder constructor.
         *
         * @param $classname => FROM de la requetez
         */
        public function __construct($classname )
        {

            self::$classname = $classname;

            $table =  strtolower(str_replace("app\\model\\","", self::$classname ));

            $this->sources[] = " `$table` ";
            $this->conditions[] = " $table.date_delete is null ";
        }

        /**
         * les chaines saisies en parametres sont recuperés pour integrer le select de la requete
         * @return $this
         */
        public function select() :Builder
        {
            foreach ( func_get_args() as $arg )$this->fields[] = $arg ;

            return $this ;

        }

        /**
         * fait un count sur le champs saisie en parametre
         * @param $fields
         * @param bool $distinct
         * @param null $alias
         * @return $this
         */
        public function count($fields, $distinct = false ,$alias = null):Builder
        {

            if(is_null($alias)){

                $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) ";

            }else {
                $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) AS $alias ";
            }
            return $this ;

        }

        /**
         * ajoute un regroupement des resultats de la requete
         * @return $this
         */
        public function group():Builder
        {

            foreach ( func_get_args() as $arg )$this->groups[] = $arg ;

            return $this ;

        }

        /**
         * ajoute une condition à la requete
         * @return $this
         */
        public function where():Builder
        {

            foreach ( func_get_args() as $arg )$this->conditions[] = $arg ;

            return $this ;

        }

        /**
         * ajoute un ordre de resultat à la requete
         * @param $order
         * @param string $sens
         * @return $this
         */
        public function order($order,$sens = "ASC"):Builder
        {

            $this->ordres[] = " $order $sens ";

            return $this ;

        }

        /**
         * ajoute une limite de résultat à la requete. doit etre superieur à 1
         * @param $limit
         * @return $this
         */
        public function limit($limit):Builder
        {
            if($limit > 1 )$this->limit = $limit ;

            return $this ;
        }

        /**
         * convertt automatiquement l'ojet en chaine de caractere.
         * @return string
         */
        public function __toString() :string
        {

            $statement = "SELECT DISTINCT " . implode(', ', $this->fields)
                . " FROM " . implode(', ', $this->sources);

            if(!empty($this->conditions))
            {
                $statement .= " WHERE ".implode( " AND " , $this->conditions);
            }

            if(!empty($this->groups))
            {
                $statement .= " GROUP BY ".implode( ", " , $this->groups);
            }

            if(!empty($this->ordres))
            {
                $statement .= " ORDER BY ".implode( ", " , $this->ordres);
            }

            if(!empty($this->limit))
            {
                $statement .= " lIMIT " . $this->limit;
            }

            $statement .= " ; " ;

            return $statement ;
        }

        /**
         * execute la requete construite
         * appel la classe DataBase
         * @param bool $one
         * @param null $attrib
         * @param bool $modif
         * @return array|Entity
         * @throws \Exception
         */
        public function execute(  $one = false, $attrib = null, $modif = false )
        {
            try {
                $exec = App::getInstance()->getDB()->query_init();

                if($attrib == null )
                {
                    return $exec->query( $this, self::$classname , $one );
                }
                elseif(is_array($attrib))
                {
                    return $exec->prepare($this, self::$classname , $one  , $attrib , $modif );
                }
            } catch (\Exception $e) {

            }

        }

        /**
         * retourne l'instance unique de l'ID en parametre
         * select * from {table} where id = ? and date_delete is null ;
         * @param $id
         * @return Entity
         * @throws \Exception
         */
        public function find($id):Entity
        {
            try {
                return $this->select("*")->where("id = ?")->execute(true, [$id]);
            } catch (\Exception $e) {
            }
        }

        /**
         * retourne la collection complete d'instance non supprimée en base
         * select * from {table} where date_delete is null ;
         * @return array
         * @throws \Exception
         */
        public function all() :Array
        {
            try {
                return $this->select("*")->execute();
            } catch (\Exception $e) {
            }
        }
    }
}