<?php
namespace app\model;

use kernel\Entity;

class Ladder extends Entity
{
    public $timer ;
    public $try ;
    public $nom ;
    public $status ;

    /**
     * @return \DateTime
     */
    public function getTimer()
    {
        $date = new \DateTime();

        $date->setTimestamp($this->timer);

        return $date;
    }

    /**
     * @return mixed
     */
    public function getTry()
    {
        return $this->try;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $timer
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;
    }

    /**
     * @param mixed $try
     */
    public function setTry($try)
    {
        $this->try = $try;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function ordering()
    {
        return $this->query("select * from ladder where date_delete is null order by timer ASC, try DESC ;" );
    }
}