<?php

class Page extends PageContainer{
    protected $title;

    public function __construct($template="index.mst", $data= []){
        parent::__construct($template, $data);
        $this->data['title']="NoName";
        $this->data['baseUrl'] = BASE_PATH."/";
    }

    public function setTitle($title){
        $this->title = $title;
        $this->data['title']=$title;
        return $this->data['title'];
    }

    public function getTitle(){
        return $this->title;
    }
}