<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/03/2022
 * Time: 23:46
 */

namespace kernel;


use PDO;
use PDOStatement;

class QueryExec
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo ;
    }
    /**
     * @param $statement
     * @param $class_name
     * @param bool $one
     * @return array
     */
    public function query( $statement, $class_name = null , $one = false  )
    {
        echo $statement;

        $req = $this->pdo->query($statement);

        $this->fetchMode($req, $class_name);

        return $this->result($req, $one);
    }

    /**
     * @param $statement
     * @param $class_name
     * @param bool $one
     * @param array $attrib
     * @param bool $isModif
     * @return array
     */
    public function prepare($statement, $class_name = null ,  $one = false, $attrib = [] , $isModif = false  )
    {
        echo $statement;

        $req = $this->pdo->prepare($statement);

        $this->bind($req , $attrib ,$isModif ) ;

        $res = $req->execute();

        if($isModif)
        {
            return $res ;
        }

        $this->fetchMode($req, $class_name);

        return $this->result( $req, $class_name, $one);
    }

    /**
     * attribue les variable de la requete preparée
     * @param PDOStatement $req
     * @param array $attrib
     * @param bool $isModif
     */
    private function bind( PDOStatement &$req , $attrib = [] , $isModif = false ){

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
     * on recupere les resultats ous la class saisie en parametres
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
     * @return array|mixed
     */
    private function result( PDOStatement $req,  $one = false )
    {
        if($one)
            return $req->fetch();

        return $req->fetchAll();
    }
}