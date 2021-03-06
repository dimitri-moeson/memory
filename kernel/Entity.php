<?php namespace kernel {

    use Exception;

    /**
     * Class Entity pour gerer en objet les données de la database
     * @package kernel
     */
    class Entity
    {
        /**
         * @var integer $id
         */
        protected $id = null  ;

        /**
         * @var \DateTime $date_create
         */
        protected $date_create ;

        /**
         * @var string $table
         */
        protected $table = null ;

        /**
         * Entity constructor.
         */
        protected function __construct()
        {
            $this->date_create = $this->datetime("date_create");
        }

        /**
         * appel en static la Class Builder qui construit les requetes SQL
         * @param $name
         * @param $arguments
         * @return Builder
         */
        public static function __callStatic($name, $arguments)
        {
            $query = new Builder( get_called_class() );

            return call_user_func_array(array( $query , $name), $arguments) ;
        }

        /**
         * Initialise une instance vierge
         * @return Entity $entity
         */
        public static function init() : Entity
        {
            $class = get_called_class();

            return new $class();
        }

        /**
         * @return integer Id
         */
        public function getId() :int
        {
            return intval($this->id);
        }

        /**
         * convertit un string date en objet DateTime
         * @param $_date
         * @return \DateTime
         */
        private function datetime($_date) : \DateTime
        {
            $date = \DateTime::createFromFormat("Y-m-d h:i:s", $this->$_date );

            if($date === false){

                $date = new \DateTime();

                if($this->$_date !== null ) {

                    list($cal, $hor) = explode(" ", $this->$_date);

                    list($y, $m, $d) = explode("-", $cal);
                    list($h, $i, $s) = explode(":", $hor);

                    $date->setDate($y, $m, $d);
                    $date->setTime($h, $i, $s);
                }
            }

            return $date ;
        }

        /**
         * @return \DateTime
         */
        public function getDateCreate()
        {
            return $this->date_create ;
        }

        /**
         * enregistre les données de l'objet en base
         * @return integer ID de l'enregistrement
         * @throws \Exception
         */
        public function save()
        {
            $vars = get_object_vars($this);

            $attributes = array();
            $values = array();

            foreach( $vars as $ch => $va)
            {
                if($ch !== 'id' && $ch !== 'date_create' && $ch !== 'table')
                {
                    if ($va !== null )
                    {
                        $attributes[$ch] = " `" . $ch . "` = :" . $ch . "";
                        $values[$ch] = $va;
                    }
                }
            }

            $set = implode(",",$attributes);

            if($this->getId() == 0 ){

                $statement = "insert into ".$this->getTable()." set ".$set." ; " ;

            }else {

                $statement = "update `".$this->getTable()."` set ".$set." where `id` = :id and `date_delete` is null ; ";
                $values['id'] = $this->getId() ;
            }

            try {

                $this->alter($statement, $values);

                if($this->getId() === 0 )
                {
                    /**
                     *  retourne le dernier enregistrement en base
                     */
                    return App::getInstance()->getDB()->lastID();
                }
                else
                {
                    /**
                     * retourne l'ID de l'Entity
                     */
                    return $this->getId();
                }

            } catch (Exception $e) {

            }
        }

        /**
         * ajoute une date de suppression et rend obsolete l'enregistrement en base
         * @return array
         * @throws Exception
         */
        public function delete()
        {
            $statement = "update `".$this->getTable()."` set `date_delete` = :date_delete where `id` = :id and `date_delete` is null ; ";

            $values['id'] = $this->getId() ;
            $values['date_delete'] = date("Y-m-d H:i:s") ;

            try {
                return $this->alter($statement, $values);
            } catch (Exception $e) {
            }
        }

        /**
         * retourne la table
         * @return string
         */
        private function getTable():string
        {
            if($this->table === null)
                $this->table = strtolower(str_replace("app\model\\","", get_called_class() ));

            return $this->table ;
        }

        /**
         * execute les requete de MAJ.
         *
         * @param $statement
         * @param $values
         * @return array
         * @throws Exception
         */
        private function alter($statement,$values)
        {
            $exec = App::getInstance()->getDB()->query_init();

            return $exec->prepare($statement,get_called_class(),true, $values , true );
        }
    }
}