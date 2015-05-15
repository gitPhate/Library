<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\Enums\OrderByOperator;

class OrderByStatement extends OtherStatement
{
    public function __construct($fields)
    {
        if(!is_array($fields))
        {
            throw new \Library\Exceptions\ArgumentException("Invalid fields");
        }
        
        parent::__construct($fields);
        
        $this->keyword = "ORDER BY";
    }
    
    public function toSql()
    {
        $buffer = $this->keyword." ";
        
        foreach($this->params as $k => $field)
        {
            $buffer .= $field[0];
            
            if($field[1] == OrderByOperator::Desc)
            {
                $buffer .= " DESC";
            }
            
            if($k != count($this->params) - 1)
            {
                $buffer .= ", ";
            }
        }
        
        return $buffer;
    }
}
?>