<?php
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../config.php';

$dbName = ""; //inserisci nome database
$author = "Andrea Carminati NUOVO";
$query = "SHOW TABLES";
$database = Database::instance();
$sth = $database->query($query);
$tables = $sth->fetchAll();
foreach($tables as $table){
    $table = $table[array_key_first($table)];
    echo "Building $table".PHP_EOL;
    $output = [];
    $retval = null;
    $cmd = "php orm.php -t $table -d $dbName -a \"$author\"";
    echo $cmd;
    exec($cmd, $output, $retval);
    $retOutput = implode(PHP_EOL, $output);
    echo $retOutput.PHP_EOL;
}