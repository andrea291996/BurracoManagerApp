<?php

class PageContainer extends PageElement{
    public function __construct($template, $data= []){
        parent::__construct($template, $data);
    }

    public function add($key, $content):PageContainer{
        $this->data[$key][] = $content; 
        return $this;
    }
    //DA FARE: REMOVE, CLEAN
}