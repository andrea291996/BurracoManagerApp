<?php
class UIMessageView extends PageElement{
    function __construct($template= "ui/messages", $data= []){
        if(count($data)==0){
            $data = UIMessage::getMessages();
            UIMessage::clean();
        }
        parent::__construct($template, $data);
    }
}