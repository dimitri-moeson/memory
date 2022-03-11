<?php
namespace kernel;

use \PDO;

/**
 * Class Database connexion à la base de données
 * @package kernel
 */
class Database
{
    private $db_name ;
    private $db_user ;
    private $db_pass ;
    private $db_host ;
    private $db_serv ;
    private $db_char ;

    private $pdo ;

    /**
     * Database constructor.
     *
     * @param $db_name
     * @param $db_user
     * @param $db_pass
     * @param string $db_host
     * @param string $db_serv
     * @param string $db_char
     */
    public function __construct($db_name,$db_user,$db_pass,$db_host = "localhost",$db_serv = "mysql",$db_char = "utf8")
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;

        $this->db_serv = $db_serv;
        $this->db_char = $db_char;
    }

    /**
     * bien separer l'instanciation de l'objet et l'appel à la base
     * @return PDO
     */
    private function getPDO() : PDO
    {
        /**
         * connexion unique à la base qui sera réutilisable
         */
        if(empty($this->pdo)) {

            $connectionString = $this->db_serv . ':'
                .'host=' . $this->db_host . ';'
                .'dbname=' . $this->db_name.';'
                .'charset=' . $this->db_char.';'
            ;

            try {
                /**
                 * Connect to database.
                 */
                $pdo = new PDO($connectionString, $this->db_user, $this->db_pass);

                /**
                 * retour d'erreur pour debbugage
                 */
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (\PDOException $e) {
                die("Error: " . $e->getMessage());
            }

            $this->pdo = $pdo;
        }

        return $this->pdo ;

    }

    /**
     * amorce l'execution d'une requete
     * @return Executer
     */
    public function query_init() : Executer
    {
        return new Executer($this->getPDO());
    }
}