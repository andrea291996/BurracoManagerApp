<?php

class RouterConfigurator {
    protected $app;

    function __construct($app){
        $this->app = $app;
    }

    function bootstrap(){
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        //DA FARE: CONTROLLO SE IL FILE ESISTE
        include_once __DIR__."/../routes.php";
        foreach($routes[$method] as $route){
            $this->app->map([$method], $route['pattern'], $route['callable']);
        }
    }
}