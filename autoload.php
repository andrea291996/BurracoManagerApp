<?php

function my_autoload(string $class){
    include __DIR__."/structure.php";
    foreach($path as $p){
        $filename = __DIR__.$p.$class.".php";
        if(file_exists($filename)){
            include_once $filename;
            break;
        }
    }
}

spl_autoload_register('my_autoload');