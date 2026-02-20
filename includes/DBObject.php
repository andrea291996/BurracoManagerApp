<?php

class DBObject implements JsonSerializable {
    protected $db = null;
    protected $tableName = null;
    protected $primaryKey = null;

    function __construct($tableName=null)
    {
        $this->tableName = $tableName;
        $this->db = Database::instance();
    }

    function setTable($tableName){
        $this->tableName = $tableName;
    }

    function getTable(){
        return $this->tableName;
    }

    function select($where=[], $fields=[]){
        if(!is_array($where))
            $where = [$this->primaryKey => $where];
        $result = $this->db->select($this->tableName, $fields, $where, [], [], "1");
        if(!empty($result)){
        $this->copy($result[0]);
        return $this->{$this->primaryKey}!=null;
        }
        return false;
    }

    function insert(array $fields = []){
        if(count($fields)==0){
            $fields = $this->toArray();
        }
        $id = $this->db->insert($this->tableName, $fields, $this->primaryKey);
        if($id>0 && $this->primaryKey){
        $this->{$this->primaryKey}=$id;
        }
        return $id;
    }

    function update($where=[], $fields=[]){
        if($this->primaryKey && count($where)==0)
            $where = [$this->primaryKey=>$this->{$this->primaryKey}];
        if(count($fields)==0)
            $fields = $this->toArray();
        $res = $this->db->update($this->tableName, $where, $fields);
        return $res;
    }

    function delete(array $where=[]){
        if($this->primaryKey && count($where)==0)
            $where = [$this->primaryKey=>$this->{$this->primaryKey}];
        $res = $this->db->delete($this->tableName, $where);
        return $res;
    }

    function copy(array $attributes){
        foreach($attributes as $k=>$v){
            $method = "set".$k;
            if(method_exists($this, $method)){
                call_user_func_array([$this, $method], [$v]);
            }
        }
        return $this;
    }

    public function toArray(){
        $records = array();
        $parent_keys = array_keys(get_class_vars(__CLASS__));
        foreach($this as $key => $value){
            if(!in_array($key, $parent_keys)){
                $records[$key] = $value;
            }
        }
    return $records;
}
    public function jsonSerialize(): mixed {
        return $this->toArray();
    }
}