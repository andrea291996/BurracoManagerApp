<?php

class Database{
    static protected $instance = null;
    /** MySql DB0 **/
    protected $dbDriver = "mysql";
    protected $dbSchema = "";
    protected $dbUser = "";
    protected $dbPassword = "";
    protected $dbHost = "";
    static protected $db = null;

    function __construct(){
        $this->dbSchema = MYSQL_DB_SCHEMA;
        $this->dbUser = MYSQL_DB_USER;
        $this->dbPassword = MYSQL_DB_PASSWORD;
        $this->dbHost = MYSQL_DB_HOST; 
        $dsn = $this->dbDriver.":host=".$this->dbHost.";dbname=".$this->dbSchema.";charset=utf8";
        try{
            self::$db = new PDO($dsn, $this->dbUser, $this->dbPassword, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        }
        catch(PDOException $e){
            die("Could not connect to the db $this->dbSchema:" . $e->getMessage());
        }    
    }

    static function instance(){
        if(self::$instance)
            return self::$instance;
        self::$instance = new Database();
        return self::$instance;
    }

    function __call($method, $args){ 
        if(self::$db && method_exists(self::$db, $method)){
            return call_user_func_Array([self::$db, $method],$args);
        }
        return null;
    }

    static function getDb(){
        return self::$db;
    }

    function buildOrderBy($orderBy=[],$orderMode=[]){
    if(is_array($orderMode) && count($orderBy)!=count($orderMode))
        return false;
    $output='';
    if(is_string($orderMode)){
        $output = implode(", ",$orderBy)." ".$orderMode;
    }
    else if(empty($orderMode)){
        $output = implode(", ",$orderBy);
    } 
    else if(is_array($orderMode)){
        $_out=[];
        foreach($orderBy as $i=>$col){
            $_out[]=$col." ".$orderMode[$i];
        }
        $output = implode(", ",$_out);
    }
    return $output;
}

    function buildWhere($where=[], &$bind=[]){
        $_where=[];
        foreach( $where as $col=>$v){
            if($v === null) { // <--- AGGIUNGI QUESTO CONTROLLO
            $_where[] = $col." IS NULL";
        }
        else if(!is_array($v))
        {
            $_where[]= $col." = ?";
            $bind[]=$v;
        }
            else{
                if(count($v)==2) 
                {
                    switch($v[0]){
                        case 'in':
                            if(is_array($v[1]))
                            {
                                $values = array_fill(0,count($v[1]),'?');
                                $v[1]="(".implode(", ",$values).")";
                                $bind = array_merge($bind, $values);
                            }
                            else if(is_string($v[1])){
                                $v[1]=trim("(",$v[1]);
                                $v[1]=trim(")",$v[1]);
                                $bind[]=$v[1];
                                $v[1]="(?)";
                            }
                        break;
                        case 'like':
                            if(is_string($v[1])){
                                $v[1]=trim("'",$v[1]);
                                $v[1]=trim("\"",$v[1]);
                                $v[1]="''".$v[1]."'";
                            }
                        break;
                    }
                    $_where[]= $col." ".$v[0]." '".$v[1]."'";
                }
                if(count($v)==1) 
                    $_where[]= $col." ".$v[0];
            }
        }
        $where = implode(" and ", $_where);
        return $where;
    }

    function insert($table, array $fields=[], $pk=null){
        if(!$table)
            return 0;
        if(count($fields)==0)
            return 0;
        $columns = implode(", ", array_keys($fields));
        $values = array_values($fields);
        $placeholders = implode(", ",array_fill(0, count($values), "?"));
        $sql = "INSERT INTO $table ($columns) VALUES($placeholders)";
        try{
            $sth = self::$db->prepare($sql);
            if($sth->execute($values)){
                if($pk){
                    $res = self::$db->lastInsertId($pk);
                }else{
                    $res = self::$db->lastInsertId();
                }
            }
            else{
                $res = 0;
            }
        }
        catch (PDOException $e){
            $res=0;
        }
        return intval($res);
    }

    function delete($table,array $where=[]){
        if(!$table)
            return 0;
        
        $bind = [];
        $where = $this->buildWhere($where,$bind);
        $sql = "DELETE FROM $table WHERE $where";
        try{
            $sth = self::$db->prepare($sql);
            if($sth->execute($bind))
                $res = $sth->rowCount();
            else
                $res = 0;
        }
        catch (PDOException $e){
            $res=0;
        }
        return intval($res);
    }

    

    function update($table,array $where=[], array $fields=[]){
        if(!$table)
            return 0;
        if(count($fields)==0)
            return 0;
        $columns = array_keys($fields);
        foreach($columns as $col){
            $assigments[] = $col."= ?";
        }
        $assigments = implode(",", $assigments);
        $bind = array_values($fields);
        $where = $this->buildWhere($where,$bind);
        $sql = "UPDATE $table SET $assigments WHERE $where";
       
        try{
            $sth = self::$db->prepare($sql);
            var_dump($sth);
            if($sth->execute($bind))
                $res = $sth->rowCount();
            else
                $res = 0;
        }
        catch (PDOException $e){
            $res=0;
        }
        return intval($res);
    }

    function select($table,array $fields=[], array $where=[], array $orderBy=[], array $orderMode=[],$limit="")
    {
        if(!$table)
            return null;
        if(count($fields)==0)  
            $fields = '*';
        else
            $fields = implode(", ",$fields);
        $bind=[];
        $where = $this->buildWhere($where,$bind);
        $orderBy = $this->buildOrderBy($orderBy,$orderMode);
        if(empty($where))
            $where = 1;
        $sql = "select $fields from 
            $table 
            where $where";
        if($orderBy)
            $sql.=" order by ".$orderBy;
        
        if($limit)
            $sql.=" limit ".$limit;
        try{
                $sth = self::$db->prepare($sql);
                $sth->execute($bind);
                $res = $sth->fetchAll();
        }
        catch (PDOException $e){
                $res=[];
        }
        return $res;
        }
}

