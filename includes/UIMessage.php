<?php
class UIMessage{
    
    static function setSuccess($msg){
        if(isset($_SESSION)){
            $_SESSION['UIMessage']['Success'][] = $msg;
        }
    }

    static function setError($msg){
        if(isset($_SESSION)){
            $_SESSION['UIMessage']['Error'][] = $msg;
        }
    }

    static function setInfo($msg){
        if(isset($_SESSION)){
            $_SESSION['UIMessage']['Info'][] = $msg;
        }
    }

    static function getMessages(){
            return isset($_SESSION['UIMessage'])?$_SESSION['UIMessage']:[];  
    }

    static function clean(){
        if(isset($_SESSION)){
            unset($_SESSION['UIMessage']);
        } 
    }
}