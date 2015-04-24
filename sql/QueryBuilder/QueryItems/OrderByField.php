<?php
namespace Library\Sql\QueryBuilder\QueryItems;

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
    
    protected function FindAlias($string, &$matches)
    {
        return false;
    }
    
    public function toSql()
    {
        $this->toStr();
    }
    
    public function toStr()
    {
        return $this->name . OrderByOperator::getKeyword($this->operator);
    }
}
?>