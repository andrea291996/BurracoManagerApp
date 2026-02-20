<?php 

abstract class Controller{
    protected $app;
    
    function __construct(){
        $this->app = Application::instance();
    }
}