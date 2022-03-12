<?php namespace app\model {

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
         * @param integer $timer
         */
        public function setTimer($timer = null ) :Ladder
        {
            if(!is_null($timer))
                $this->timer = $timer;

            return $this;
        }

        /**
         * @param integer $try
         */
        public function setTry($try = null ) :Ladder
        {
            if(!is_null($try))
                $this->try = $try;

            return $this;
        }

        /**
         * @param string $nom
         */
        public function setNom($nom = null ) :Ladder
        {
            if(!is_null($nom))
                $this->nom = $nom;

            return $this;
        }

        /**
         * @param string $status
         */
        public function setStatus($status = null ) :Ladder
        {
            if(!is_null($status))
                $this->status = $status;

            return $this;
        }
    }
}