<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/03/2022
 * Time: 08:19
 */

namespace kernel;

/**
 * Class Fluent Builder
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

    public function select(){

        foreach ( func_get_args() as $arg )$this->fields[] = $arg ;

        return $this ;

    }

    public function count($fields, $distinct = false ,$alias = null){

        if(is_null($alias)){

            $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) ";

        }else {
            $this->fields[] = " count( ".($distinct ? "DISTINCT" : "" )." `$fields`) AS $alias ";
        }
        return $this ;

    }

    public function group(){

        foreach ( func_get_args() as $arg )$this->groups[] = $arg ;

        return $this ;

    }

    public function where(){

        foreach ( func_get_args() as $arg )$this->conditions[] = $arg ;

        return $this ;

    }

    public function __construct($class  ){

        $this->classname = $class;

        $table =  strtolower(str_replace("app\\model\\","",$class ));

        $this->sources[] = " `$table` ";
        $this->conditions[] = " $table.date_delete is null ";

        return $this ;
    }

    public function order($order,$sens = "ASC"){

        $this->ordres[] = " $order $sens ";

        return $this ;

    }

    public function limit($limit)
    {
        $this->limit = $limit ;

        return $this ;
    }

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
    public function execute($one = false ,$attrib = null , $modif = false )
    {
        $exec = App::getInstance()->getDB()->query_init();

        if($attrib === null )
        {
            return $exec->query( $this, $this->classname , $one );
        }
        else
        {
            return $exec->prepare($this, $this->classname , $one  , $attrib , $modif );
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

    /**
     * @return string
     */
    private function table()
    {
        return strtolower(str_replace("app\\model\\","",get_called_class() ));
    }


    /**
     * @return array
     */
    public function save(Entity $obj)
    {
        $vars = get_object_vars($obj);

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

        if($obj->getId() == 0 ){

            $statement = "insert into ".$this->table()." set ".$set." ; " ;

        }else {

            $statement = "update ".$this->table()." set ".$set." where id = :id and date_delete is null ; ";
            $values['id'] = $obj->getId() ;
        }

        $exec = App::getInstance()->getDB()->query_init();

        return $exec->prepare($statement,true, $values , true );
    }

    /**
     * @return array
     */
    public function delete($obj)
    {
        $statement = "update ".$this->table()." set date_delete = :date_delete where id = :id ; ";

        $values['id'] = $obj->getId() ;
        $values['date_delete'] = date("Y-m-d H:i:s") ;

        $exec = App::getInstance()->getDB()->query_init();

        return $exec->prepare($statement,true, $values , true );
    }

}