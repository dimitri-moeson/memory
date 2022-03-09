<?php
namespace kernel;

/**
 * Class Config
 * @package kernel
 */
class Config
{
    private $settings = array();

    private static $_instance ;

    public static function getInstance($file = null )
    {
        if(self::$_instance === null)
        {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }

    /**
     * Config constructor.
     * @param $file
     */
    private function __construct($file)
    {
        $this->settings = parse_ini_file($file, true); //require $file;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getDB($key)
    {
        return $this->get('database',$key);
    }

    public function get($dom, $key){

        $hasBase = array_key_exists($dom, $this->settings);
        $hasKeys = array_key_exists($key, $this->settings[$dom]);

        if (!$hasBase || !$hasKeys ) {
            throw new \Exception("Missing config [$dom/$key] informations");
        }

        return $this->settings[$dom][$key];
    }

}