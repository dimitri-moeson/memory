<?php
namespace app\model;

use kernel\Entity;

/**
 * Class Ladder modele des données des joueurs et de leur parties en base
 * @package app\model
 */
class Ladder extends Entity
{
    /**
     * @var \DateTime $timer
     */
    public $timer ;
    /**
     * @var integer $try
     */
    public $try ;
    /**
     * @var string $nom
     */
    public $nom ;
    /**
     * @var string $status
     */
    public $status ;

    /**
     * @return \DateTime
     */
    public function getTimer() : \DateTime
    {
        $date = new \DateTime();

        $date->setTimestamp($this->timer);

        return $date;
    }

    /**
     * @return integer
     */
    public function getTry(): int
    {
        return $this->try;
    }

    /**
     * @return string
     */
    public function getNom() :string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getStatus() :string
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
}