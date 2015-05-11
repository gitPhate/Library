<?php
namespace Library\Sql\QueryBuilder\QueryItems;

use Library\Sql\QueryBuilder\Enums\OrderByOperator;

class OrderByField extends QueryItem
{
    public $operator;
    
    public function __construct($string, $operator)
    {
        if(!OrderByOperator::isValidValue($operator))
        {
            throw new Exceptions\ArgumentException("Invalid operator");
        }
        
        parent::__construct($string, null);
        
        $this->operator = $operator;
    }
    
    public function toSql()
    {
        return $this->name . OrderByOperator::getKeyword($this->operator);
    }
}
?>