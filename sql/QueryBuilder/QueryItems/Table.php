<?php
namespace Library\Sql\QueryBuilder\QueryItems;

class Table extends QueryItem
{
    public function __construct($string, $alias = null)
    {
        parent::__construct($string, $alias);
    }
    
    public function toSql()
    {
        $buffer = ($this->is_query) ? "(".$this->name->toSql().")" : $this->name;
        
        if($this->has_alias)
        {
            $buffer .= " ".$this->alias;
        }
        
        return $buffer;
    }
}
?>