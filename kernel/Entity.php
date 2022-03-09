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

    /**
     * @param $statement
     * @param bool $one
     * @param null $attrib
     * @param bool $modif
     * @return array
     */
    public function query($statement,$one = false ,$attrib = null , $modif = false )
    {
        $class = get_called_class();
        $exec = App::getInstance()->getDB()->query_init();

        if($attrib === null )
        {
            return $exec->query( $statement, $class ,$one );
        }
        else
        {
            return $exec->prepare($statement, $class, $one  , $attrib , $modif );
        }
    }

    /**
     * @return string
     */
    private function table()
    {
        return strtolower(str_replace("app\\model\\","",get_called_class() ));
    }

    /**
     * @param $id
     * @return Entity
     */
    public function find($id)
    {
        return $this->query("select * from ".$this->table()." where id = ? and date_delete is null ;",true , [$id] );
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->query("select * from ".$this->table()." where date_delete is null ;");
    }

    /**
     * @return array
     */
    public function save()
    {
        $vars = get_object_vars($this);

        $attrib = $values = array();

        foreach( $vars as $ch => $va)
        {
            if ($va !== null && $ch !== 'id' && $ch !== 'date_create')
            {
                $attrib[$ch] = " `".$ch."` = :".$ch."" ;
                $values[$ch] = $va ;
            }
        }

        $set = implode(",",$attrib);

        if($this->getId() == 0 ){

            $statement = "insert into ".$this->table()." set ".$set." ; " ;

        }else {

            $statement = "update ".$this->table()." set ".$set." where id = :id and date_delete is null ; ";
            $values['id'] = $this->getId() ;
        }

        return $this->query($statement,true, $values , true );
    }

    /**
     * @return array
     */
    public function delete()
    {
        $statement = "update ".$this->table()." set date_delete = :date_delete where id = :id ; ";

        $values['id'] = $this->getId() ;
        $values['date_delete'] = date("Y-m-d H:i:s") ;

        return $this->query($statement,true, $values , true );
    }
}