<?php

use Slim\Factory\AppFactory;

class Application{
    protected $app;
    static protected $instance = null;
    
    function __construct(){
        $this->app = AppFactory::create();  
        }
    static function instance(){  
        if(!self::$instance)
            self::$instance = new Application();
        return self::$instance;
    }

    function __call($method, $args){ 
        if(method_exists($this->app, $method)){
            return call_user_func_Array([$this->app, $method],$args);
        }
        return null;
    }
}