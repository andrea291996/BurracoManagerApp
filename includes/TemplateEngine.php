<?php

class TemplateEngine {
    protected $engine;
    protected static $instance=null; 

    function __construct() {
       $options = ['extension' => TENGINE_TEMPLATE_FILE_EXTENSION];       
        $this->engine = new \Mustache\Engine([
            'cache'=>TENGINE_CACHE_FOLDER,
            'cahce_file_mode'=>TENGINE_CACHE_FILE_MODE,
            'entity_flags' => ENT_QUOTES,
            'loader' => new \Mustache\Loader\FilesystemLoader(TEMPLATES_FOLDER, $options),  
            'partials_loader' => new \Mustache\Loader\FilesystemLoader(TEMPLATES_FOLDER, $options) 
        ]);
    }

    function render($template, $data=[]){   
        return $this->engine->render($template, $data);
    }

    static function instance(){  
        if(!self::$instance)
            self::$instance = new TemplateEngine();
        return self::$instance;
    }
}