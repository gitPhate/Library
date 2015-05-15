<?php
namespace Library\Sql\QueryBuilder\QueryItems;

class SelectField extends QueryItem
{
    public $is_distinct;
    
    public function __construct($string, $alias = null, $distinct = false)
    {
        parent::__construct($string, $alias);
        
        $this->is_distinct = $distinct;
    }
    
    public function toSql()
    {
        $buffer = (($this->is_distinct) ? "DISTINCT " : "").$this->name;
        
        if($this->has_alias)
        {
            $buffer .= " AS ".$this->alias;
        }
        
        return $buffer;
    }
}
?>