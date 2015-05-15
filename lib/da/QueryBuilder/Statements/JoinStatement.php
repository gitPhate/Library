<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\QueryItems;
use Library\Utilities\UtilitiesService;
use Library\Sql\QueryBuilder\Enums;
use Library\Exceptions as Excs;

class JoinStatement extends BaseStatement
{
    public $join_type;
    public $table;
    
    public function __construct($type, $table, $alias, $params)
    {
        if(!Enums\JoinType::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid join type");
        }
        
        if(!is_string($table))
        {
            throw new Excs\ArgumentException("Invalid join table");
        }
        
        parent::__construct(Enums\StatementType::Join, array(new QueryItems\JoinCondition(Enums\JoinConditionType::First, $params)));
        
        $this->table = new QueryItems\Table($table, $alias);
        $this->join_type = $type;
    }
    
    public function AddCondition($type, $params)
    {
        $this->params[] = new QueryItems\JoinCondition($type, $params);
    }
    
    public function toSql()
    {
        $buffer = Enums\JoinType::getKeyword($this->join_type)." JOIN ".$this->table->toSql()." ";
        
        foreach($this->params as $param)
        {
            $buffer .= $param->toSql()." ";
        }
        
        return $buffer;
    }
}
?>