<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/03/2022
 * Time: 20:32
 */

namespace kernel;


class GlobalData
{
    private static $_instance ;

    private $method;

    /**
     * @return GlobalData
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance)){
            self::$_instance = new GlobalData();
        }
        return self::$_instance;

    }

    /**
     * GlobalData constructor.
     */
    private function __construct()
    {
        $this->method = getenv('REQUEST_METHOD');
    }

    /**
     * @return bool
     */
    public function submitted()
    {
        if($this->method === 'POST')
            if(!empty($_POST))
                return true ;

        return false ;
    }

    /**
     * @return mixed
     */
    public function content()
    {
        if($this->method === 'POST')
            return $_POST;

        return $_REQUEST ;
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key){

        if($this->method === 'POST')
            if( isset($_POST[$key]) )
                return array_key_exists($key, $_POST) ;

        if (isset($_GET[$key]))
            return array_key_exists($key, $_GET);

        if (isset($_REQUEST[$key]))
            return array_key_exists($key, $_REQUEST);

        return false;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key , $default = null ){

        if($this->method === 'POST')
            if(array_key_exists($key, $_POST))
                return $_POST[$key];

        if(array_key_exists($key, $_GET))
            return $_GET[$key];

        if(array_key_exists($key, $_REQUEST))
            return $_REQUEST[$key];

        return $default ;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value){

        if($this->method === 'POST')
        {
            $_POST[$key] = $value ;
            return;
        }

        $_REQUEST[$key] = $value ;
        return ;
    }

    /**
     * @param $key
     * @return bool
     */
    public function delete($key){

        if ($this->exists($key)) {

            if($this->method === 'POST')
            {
                unset($_POST[$key]) ;
                return true ;
            }

            unset($_REQUEST[$key]);
            return true ;
        }

        return false ;
    }
}