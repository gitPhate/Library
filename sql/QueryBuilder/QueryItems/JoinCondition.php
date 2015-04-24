<?php
namespace Library\Sql\QueryBuilder\QueryItems;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Sql\QueryBuilder\Enums;
use Library\Exceptions as Excs;

class JoinCondition
{
    public $condition_type;
    public $params;
    
    public function __construct($type, $params)
    {
        if(!Enums\JoinConditionType::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid join condition type");
        }
        
        if(!is_array($params))
        {
            throw new Excs\ArgumentException("Invalid join condition params");
        }
        
        $this->condition_type = $type;
        $this->params = $params;
    }
    
    public function toSql()
    {
        $left = $this->params[0].".".$this->params[1];
        $operator = $this->params[2];
        $right = $this->params[3].((isset($this->params[4])) ? ".".$this->params[4] : "");
        
        return Enums\JoinConditionType::getKeyword($this->condition_type)." {$left} {$operator} {$right}";
    }
}