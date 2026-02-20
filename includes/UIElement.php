<?php

abstract class UIElement{
    protected $template;
    protected $data;

    public function __construct($template= "", $data= []){
        $this->template = $template;
        $this->data = $data;
    }

    public function render(){
        return TemplateEngine::instance()->render($this->template, $this->data);
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function setTemplate($template){
        $this->template = $template;
        return $this;
    }
}