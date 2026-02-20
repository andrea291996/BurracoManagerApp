<?php

class PageConfigurator{
    protected $page;
    static protected $instance = null;

    function __construct(Page $page=null){
        $this->page = $page;
    }

    function setPage(Page $page){
        $this->page = $page;
    }

    function getPage(){
        return $this->page;
    }

    static function instance(){   
        if(!self::$instance)
            self::$instance = new PageConfigurator();
        return self::$instance;
        }
}