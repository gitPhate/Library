<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Sql\QueryBuilder\Statements\WhereStatement;
use Library\Sql\QueryBuilder\Enums\WhereType;
use Library\Utilities\UtilitiesService;

abstract class BaseQuery
{
    public static $PrimaryOperators = array("=", "<>", ">", "<", ">=", "<=", "!=");
    public static $WhereOperators = array("BETWEEN", "LIKE", "IS", "IN", "NOT IN", "IS NOT");
    
    protected $table;
    protected $statements;
    protected $query;
    
    protected $raw_query_flag;

    public function __construct()
    {
        $this->query = null;
        $this->table = null;
        $this->statements = array();
        
        $this->raw_query_flag = false;
    }
    
    public function RawQuery($query)
    {
        if(!$this->raw_query_flag)
        {
            throw new Excs\InvalidOperationException("Can't call RawQuery() in this context");
        }
        
        $this->query = $query;
    }
    
    public function From($table, $alias = null)
    {
        $this->table = new QueryItems\Table($table, $alias);
        
        return $this;
    }
    
    private function WhereClause($cond, $params, $type, $not = false)
    {
        if(!is_string($cond))
        {
            throw new Excs\ArgumentException("condition must be a string");
        }
        
        if(!WhereType::isValidValue($type))
        {
            throw new Excs\ArgumentException("invalid type");
        }
        
        if(is_null($params))
        {
            $field = $cond;
            $op = "IS".(($not) ? " NOT" : "");
            $matches = array("NULL");
        }
        else
        {
            if(preg_match("/\?|\:/", $cond))
            {
                $matches = ConditionParser::ParseWhereClause($cond);
        
                if(empty($matches) && !is_null($params))
                {
                    throw new Excs\InvalidOperationException("Invalid where clause");
                }
                
                if($not)
                {
                    throw new Excs\InvalidOperationException("Cannot call WhereNot() in this context");
                }
                
                $matches = (!is_array($params)) ? ParamsBinder::BindSingleParam($matches, $params) : $matches = ParamsBinder::BindParams($matches, $params);
                $field = array_shift($matches);
                $op = array_shift($matches);
            }
            else
            {
                if(is_array($params) || UtilitiesService::GetParentClassName($params) == "BaseQuery")
                {
                    $field = $cond;
                    $op =  (($not) ? "NOT " : "").'IN';
                    $matches = $params;
                }
                elseif(is_string($params) || is_numeric($params))
                {
                    $field = $cond;
                    $op = ($not) ? "!=" : "=";
                    $matches = array(UtilitiesService::WrapInQuotes($params));
                }
                else
                {
                    //var_dump($cond, $params, $type, $not);
                    throw new Excs\InvalidOperationException("Invalid where clause");
                }
            }
        }
        
        $this->statements[] = new Statements\WhereStatement($field, $op, $matches, $type);
        
        return $this;
    }
    
    public function Where($cond = null, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::First);
    }
    
    public function WhereNot($cond, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::First, true);
    }
    
    public function AndWhere($cond, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::AndWhere);
    }
    
    public function OrWhere($cond, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::OrWhere);
    }
    
    public function AndWhereNot($cond, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::AndWhere, true);
    }
    
    public function OrWhereNot($cond, $params = null)
    {
        return $this->WhereClause($cond, $params, WhereType::OrWhere, true);
    }
    
    //GroupBy, Having, OrderBy, Limit
    
    public function GroupBy()
    {
        $fields = func_get_args();
        $this->statements[] = new Statements\GroupByStatement($fields);
        return $this;
    }
    
    public function OrderBy($fields)
    {
        $orderByFields = array();
        
        if(is_string($fields))
            $fields = array($fields);
        
        foreach($fields as $field)
        {
            if(preg_match(ConditionParser::$asc_desc_regex, $field, $matches))
            {
                $orderByFields[] = array($matches[1], (!isset($matches[2]) || $matches[2] == "ASC") ? Enums\OrderByOperator::Asc : Enums\OrderByOperator::Desc);
            }
        }
        
        $this->statements[] = new Statements\OrderByStatement($orderByFields);
        
        return $this;
    }
    
    public function Having($function, $cond = null, $param = null)
    {
        $matches = "";
        
        if(is_null($cond) && is_null($param))
        {
            $matches = array("COUNT", $function, ">", 0);
        }
        else
        {
            $matches = ConditionParser::ParseHavingClause($function, $cond);
            
            if(empty($matches))
            {
                throw new Excs\ArgumentException("Invalid condition");
            }
            
            $matches = ParamsBinder::BindSingleParam($matches, $param);
        }
        
        $this->statements[] = new Statements\HavingStatement($matches);
        
        return $this;
        
    }
    
    public function Limit()
    {
        $args = func_get_args();
        
        if(count($args) > 2)
        {
            throw new Excs\ArgumentException("Invalid arguments");
        }
        
        if(count($args) == 1)
        {
            $row_count = $args[0];
            $offset = 0;
        }
        else
        {
            $offset = $args[0];
            $row_count = $args[1];
        }
        
        $this->statements[] = new Statements\LimitStatement($offset, $row_count);
        
        return $this;
    }
    
    protected function PrepareFromClause()
    {
        $this->query .= "FROM ".$this->table->toSql()./*"<br />\r\n".*/" ";
    }
    
    protected function PrepareWhereClause()
    {
        $stats = $this->SelectStatemementsOfType(Enums\StatementType::Where);
        
        if(!empty($stats))
        {
            $this->query .= "WHERE";
        
            foreach($stats as $stat)
            {
                $this->query .= WhereType::getKeyword($stat->where_type)." ".$stat->toSql()./*"<br />\r\n".*/" ";
            }
        }
    }
    
    protected function PrepareOtherClauses()
    {
        $stats = $this->SelectStatemementsOfType(Enums\StatementType::Other);
        
        if(!empty($stats))
        {
            foreach($stats as $stat)
            {
                $this->query .= $stat->toSql()./*"<br />\r\n".*/" ";
            }
        }   
    }
    
    protected function SelectStatemementsOfType($type)
    {
        if(!Enums\StatementType::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        $return = array();
        
        foreach($this->statements as $stat)
        {
            if($stat->type == $type)
            {
                $return[] = $stat;
            }
        }
        
        return $return;
    }
    
    public abstract function toSql();
}
?>