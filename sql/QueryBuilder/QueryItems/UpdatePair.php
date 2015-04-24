<?php
namespace Library\Sql\QueryBuilder\QueryItems;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class UpdatePair
{
    private $field;
    private $value;
    
    private $query_flag;
    
    public function __construct($field, $value)
    {
        if(!is_string($field))
        {
            throw new Excs\ArgumentException("Invalid field");
        }
        
        if(is_object($value) && UtilitiesService::GetClassName($value) != "SelectQuery")
        {
            throw new Excs\ArgumentException("Invalid value");
        }
        
        if(is_object($value))
        {
            $this->query_flag = true;
        }
        else
        {
            $this->query_flag = false;
        }
        
        $this->field = $field;
        $this->value = $value;
    }
    
    public function toSql()
    {
        $buffer = $this->field." = ";
        
        if($this->query_flag)
        {
            $buffer .= "(".$this->value->toSql().")";
        }
        else
        {
            $buffer .= UtilitiesService::WrapInQuotes($this->value);
        }
        
        return $buffer;
    }
}
?>