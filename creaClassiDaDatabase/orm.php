<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../secret.php';

function help(){
    echo "Usage:".PHP_EOL;
    echo "orm.php <options>".PHP_EOL;
    echo "".PHP_EOL;
    echo "Options list:".PHP_EOL;
    echo "-t <table name> (required)".PHP_EOL;
    echo "-d <database name> (required)".PHP_EOL;
    echo "-a <author name> (optional)".PHP_EOL;
    echo "-h this help".PHP_EOL;
    echo "".PHP_EOL;
    echo "Credits ANDREA CARMINATI".PHP_EOL;
}

$options = getopt("t:d:a:h");
if(isset($options['h']) || empty($options)){
    help();
    exit;
}

if(!isset($options['t']) || !isset($options['d'])){
    if(!isset($options['t'])){
        echo "Table name option is missing!".PHP_EOL;
    }
    if(!isset($options['d'])){
        echo "Database name option is missing!".PHP_EOL;
    }
    echo "Please see help for details!".PHP_EOL;
}

if(!isset($options['a'])){
    $options['a'] = 'Andrea Carminati (default)';
}

echo "Start class builder".PHP_EOL;

$database = Database::instance();

$table = $options['t'];
$schema = $options['d'];
$query = "DESCRIBE $table";
$class = ucfirst($table);
$data = [
    'classname'=>$class,
    'date' => date("Y-m-d H:i:s"),
    'author'=>$options['a'],
    'table'=>$table,
    'database'=>$schema
];

$data['macro']=strtoupper($table);
$sth = $database->query($query);
$attributes = $sth->fetchAll();
if(!is_array($attributes))
    exit;
$data['attributes']=[];

foreach($attributes as $k=>$attr){
    $data['attributes'][]=['name'=>$attr['Field']];
   
    if($attr['Key']=='PRI')
        $data['pk']=$attr['Field'];
}

$template = "classOrm.mst";
$engine = TemplateEngine::instance();
$classStr = $engine->render($template,$data);
file_put_contents("../models/$class.php",$classStr);

echo "End class builder".PHP_EOL;