<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;

class InsertQuery extends BaseQuery
{
    private $fields;
    private $select_query;
    private $query_flag;
    
    public function __construct($fields)
    {
        if(!is_array($fields) && !UtilitiesService::AreAllStrings($fields))
        {
            throw new Excs\ArgumentException("Invalid fields");
        }
        
        $this->fields = $fields;
        $this->query_flag = false;
        
        return $this;
    }
    
    public function Into($string)
    {
        $this->table = new QueryItems\Table($string);
        
        return $this;
    }
    
    public function Values($fields)
    {
        if(!$this->CheckFieldsKeys($fields))
        {
            throw new Excs\ArgumentException("Invalid fields. Keys of the array must match the array provided in the constructor");
        }
        
        $this->fields = $fields;
        
        return $this;
    }
    
    public function FromQuery($query)
    {
        if(UtilitiesService::GetClassName($query) != "SelectQuery")
        {
            throw new Excs\ArgumentException("Invalid query. Query must be an instance of SelectQuery");
        }
        
        $this->select_query = $query;
        
        $this->query_flag = true;
        
        return $this;
    }
    
    public function toSql()
    {
        $this->query = "INSERT INTO ".$this->table->toSql()."(";
        $keys = array_keys($this->fields);
        
        foreach($keys as $k)
        {
            $this->query .= $k;
            
            if($k != end($keys))
            {
                $this->query .= ", ";
            }
        }
        
        $this->query .= ") ";
        
        if($this->query_flag)
        {
            $this->query .= $this->select_query->toSql();
        }
        else
        {
            $this->query .= "VALUES(";

            foreach($this->fields as $f)
            {
                $this->query .= UtilitiesService::WrapInQuotes($f);
                
                if($f != end($this->fields))
                {
                    $this->query .= ", ";
                }
            }
            
            $this->query .= ")";
        }
        
        return $this->query;
    }
    
    private function CheckFieldsKeys($fields)
    {
        if(!is_array($fields))
        {
            throw new Excs\ArgumentException("Invalid array");
        }
        
        foreach(array_keys($fields) as $k)
        {
            if(!in_array($k, $this->fields))
            {
                return false;
            }
        }
        
        return true;
    }
}
?>