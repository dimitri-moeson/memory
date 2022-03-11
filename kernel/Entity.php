<?php
namespace kernel;

/**
 * Class Entity
 * @package kernel
 */
class Entity
{
    /**
     * @var int $id
     */
    protected $id = null  ;

    /**
     * @var \DateTime $date_create
     */
    protected $date_create ;

    /**
     * @var \DateTime $date_update
     */
    protected $date_update ;

    /**
     * @var \DateTime $date_delete
     */
    protected $date_delete ;

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
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $class = get_called_class();

        $query = new Builder( $class );

        return call_user_func_array(array( $query , $name), $arguments) ;
    }

    /**
     * @return mixed Entity
     */
    public static function init()
    {
        $class = get_called_class();

        return new $class();
    }

    /**
     * @return integer Id
     */
    public function getId()
    {
        return intval($this->id);
    }

    /**
     * @param $_date
     * @return \DateTime
     */
    private function datetime($_date)
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
     * @return mixed
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @return mixed
     */
    public function getDateDelete()
    {
        return $this->date_delete;
    }

}