<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/03/2022
 * Time: 08:19
 */

namespace kernel;

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
     * @param $classname
     */
    public function __construct($classname ){

        self::$classname = $classname;

        $table =  strtolower(str_replace("app\\model\\","", self::$classname ));

        $this->sources[] = " `$table` ";
        $this->conditions[] = " $table.date_delete is null ";

        return $this ;
    }

    /**
     * @return $this
     */
    public function select(){

        foreach ( func_get_args() as $arg )$this->fields[] = $arg ;

        return $this ;

    }

    /**
     * @param $fields
     * @param bool $distinct
     * @param null $alias
     * @return $this
     */
    public function count($fields, $distinct = false ,$alias = null){

        if(is_null($alias)){

            $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) ";

        }else {
            $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) AS $alias ";
        }
        return $this ;

    }

    /**
     * @return $this
     */
    public function group(){

        foreach ( func_get_args() as $arg )$this->groups[] = $arg ;

        return $this ;

    }

    /**
     * @return $this
     */
    public function where(){

        foreach ( func_get_args() as $arg )$this->conditions[] = $arg ;

        return $this ;

    }

    /**
     * @param $order
     * @param string $sens
     * @return $this
     */
    public function order($order,$sens = "ASC"){

        $this->ordres[] = " $order $sens ";

        return $this ;

    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit ;

        return $this ;
    }

    /**
     * @return string
     */
    public function __toString(){

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
     * @param bool $one
     * @param null $attrib
     * @param bool $modif
     * @return array
     */
    public function execute(  $one = false, $attrib = null, $modif = false )
    {
        $exec = App::getInstance()->getDB()->query_init();

        if($attrib == null )
        {
            return $exec->query( $this, self::$classname , $one );
        }
        elseif(is_array($attrib))
        {
            return $exec->prepare($this, self::$classname , $one  , $attrib , $modif );
        }
    }

    /**
     * select * from {table} where id = ? and date_delete is null ;
     * @param $id
     * @return Entity
     */
    public function find($id)
    {
        return $this->select("*")->where("id = ?")->execute(true , [$id] );
    }

    /**
     * select * from {table} where date_delete is null ;
     * @return array
     */
    public function all()
    {
        return $this->select("*")->execute();
    }

}