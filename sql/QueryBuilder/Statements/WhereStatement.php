<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Sql\QueryBuilder\Enums;
use Library\Exceptions as Excs;

class WhereStatement extends BaseStatement
{
    public $field;
    public $operator;
    public $where_type;
    
    public function __construct($field, $operator, $params, $type = Enums\WhereType::First)
    {
        if(!$this->validateOperator($operator))
        {
            throw new Excs\ArgumentException("Invalid operator");
        }
        
        if(!Enums\WhereType::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        parent::__construct(Enums\StatementType::Where, $params);
        
        $this->field = $field;
        $this->operator = $operator;
        $this->where_type = $type;
    }
    
    private function validateOperator($op)
    {
        return in_array($op, BaseQuery::$PrimaryOperators) || in_array($op, BaseQuery::$WhereOperators);
    }
    
    public function toSql()
    {
        $buffer = "";
        
        if(preg_match("/in/i", $this->operator))
        {
            $buffer .= "{$this->field} {$this->operator} (";
            
            foreach($this->params as $k => $param)
            {
                $buffer .= $param.(($k != count($this->params) - 1) ? ", " : "");
            }
            
            $buffer .= ")";
        }
        elseif(strtolower($this->operator) == "between")
        {
            $buffer .= "{$this->field} BETWEEN {$this->params[0]} AND {$this->params[1]}";
        }
        else
        {
            $buffer .= "{$this->field} {$this->operator} {$this->params[0]}";
        }
        
        return $buffer;
    }
}
?>