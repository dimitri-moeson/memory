<?php
namespace kernel;

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
     * Entity constructor.
     */
    protected function __construct()
    {
        $this->date_create = $this->datetime("date_create");
    }

    /**
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
     * @param $_date
     * @return \DateTime
     */
    private function datetime($_date) : \DateTime
    {
        $date =   \DateTime::createFromFormat("Y-m-d h:i:s", $this->$_date );

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
     * @return array
     */
    public function save()
    {
        $vars = get_object_vars($this);

        $table =  strtolower(str_replace("app\\model\\","", get_called_class() ));

        $attributes = array();
        $values = array();

        foreach( $vars as $ch => $va)
        {
            if ($va !== null && $ch !== 'id' && $ch !== 'date_create')
            {
                $attributes[$ch] = " `".$ch."` = :".$ch."" ;
                $values[$ch] = $va ;
            }
        }

        $set = implode(",",$attributes);

        if($this->getId() == 0 ){

            $statement = "insert into ".$table." set ".$set." ; " ;

        }else {

            $statement = "update `".$table."` set ".$set." where `id` = :id and `date_delete` is null ; ";
            $values['id'] = $this->getId() ;
        }

        return $this->alter($statement,$values);
    }

    /**
     * rend obsolete l'enregistrement en base
     * @return array
     */
    public function delete()
    {
        $table =  strtolower(str_replace("app\\model\\","", get_called_class() ));

        $statement = "update `".$table."` set `date_delete` = :date_delete where `id` = :id and `date_delete` is null ; ";

        $values['id'] = $this->getId() ;
        $values['date_delete'] = date("Y-m-d H:i:s") ;

        return $this->alter($statement,$values);
    }

    /**
     * execute les requete de MAJ.
     *
     * @param $statement
     * @param $values
     * @return array
     * @throws \Exception
     */
    private function alter($statement,$values)
    {
        $exec = App::getInstance()->getDB()->query_init();

        return $exec->prepare($statement,"".get_called_class(),true, $values , true );
    }
}