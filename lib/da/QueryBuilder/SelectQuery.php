<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;
use Library\Sql\QueryBuilder\ConditionParser;
use Library\Sql\QueryBuilder\Enums;

class SelectQuery extends BaseQuery
{
	private $fields;
	private $last_join_statement_index;
    private $union_flag;
	
	public function __construct()
	{
        parent::__construct();
        
		$this->fields = array();
		$this->last_join_statement_index = -1;
        $this->union_flag = false;
		
		return $this;
	}
	
	public function Select($select, $alias, $distinct = false)
	{
		if(is_array($select) && is_null($alias))
		{
			foreach($select as $k => $v)
			{
				if(is_numeric($k))
				{
					$k = null;
				}
                
                if(is_array($v))
                {
                    if(!is_string($v[0]) || !is_bool($v['distinct']))
                    {
                        throw new Excs\ArgumentException("Invalid format of the select field");
                    }
                    
                    $select = $v[0];
                    $distinct = $v['distinct'];
                }
                else
                {
                    $select = $v;
                    $distinct = false;
                }
				
				$this->fields[] = new QueryItems\SelectField($select, $k, $distinct);
			}
		}
		elseif(is_string($select) && (is_null($alias) || (!is_null($alias) && is_string($alias))))
		{
			$this->fields[] = new QueryItems\SelectField($select, $alias, $distinct);
		}
		else
		{
			throw new Excs\ArgumentException("Invalid select");
		}
		
		return $this;
	}
	
	public function InnerJoin()
	{
		$args = func_get_args();
		$table = $args[0];
		$alias = null;
		$condition = $args[1];
        $params = null;
		
		if(isset($args[2]))
		{
			$alias = $args[1];
			$condition = $args[2];
		}
		
		return $this->Join(Enums\JoinType::Inner, $table, $alias, $condition, null);
	}
	
	public function LeftJoin()
	{
		$args = func_get_args();
		$table = $args[0];
		$alias = null;
		$condition = $args[1];
		
		if(isset($args[2]))
		{
			$alias = $args[1];
			$condition = $args[2];
		}
		
		return $this->Join(Enums\JoinType::Left, $table, $alias, $condition, null);
	}
	
	public function RightJoin()
	{
		$args = func_get_args();
		$table = $args[0];
		$alias = null;
		$condition = $args[1];
		
		if(isset($args[2]))
		{
			$alias = $args[1];
			$condition = $args[2];
		}
		
		return $this->Join(Enums\JoinType::Right, $table, $alias, $condition, null);
	}
	
	public function AndCondition($condition, $param = null)
	{
		return $this->AddJoinCondition(Enums\JoinConditionType::AndOn, $condition, $param);
	}
	
	public function OrCondition($condition, $param = null)
	{
		return $this->AddJoinCondition(Enums\JoinConditionType::OrOn, $condition, $param);
	}
	
	private function Join($type, $table, $alias, $condition, $params)
	{
		if(!is_string($condition))
		{
			throw new Excs\ArgumentException("Condition must be a string");
		}
		
		$matches = ConditionParser::ParseJoinOnClause($condition);
		
		if(empty($matches))
		{
			throw new Excs\ArgumentException("Invalid condition");
		}
		
		if(!is_null($params))
		{
			$matches = ParamsBinder::BindSingleParam($matches, $params);
		}
		
		$statement = new Statements\JoinStatement($type, $table, $alias, $matches);
		$this->statements[] = $statement;
		$this->last_join_statement_index = array_search($statement, $this->statements);
		
		return $this;
	}
	
	private function AddJoinCondition($type, $condition, $params = null)
	{
		if(!is_string($condition))
		{
			throw new Excs\ArgumentException("Condition must be a string");
		}
		
		$matches = ConditionParser::ParseJoinOnClause($condition);
		
		if(empty($matches))
		{
			throw new Excs\ArgumentException("Invalid condition");
		}
		
		if(!is_null($params))
		{
			$matches = ParamsBinder::BindSingleParam($matches, $params);
		}
		
		$this->statements[$this->last_join_statement_index]->AddCondition($type, $matches);
		
		return $this;
	}
    
    public function Union($query)
    {
        return $this->UnionClause($query);
    }
    
    public function UnionAll($query)
    {
        return $this->UnionClause($query, true);
    }
    
    private function UnionClause($query, $all = false)
    {
        $this->statements[] = new Statements\UnionStatement($query, $all);
        $this->union_flag = true;
        
        return $this;
    }
	
	private function prepareJoinClause()
	{
		$stats = $this->SelectStatemementsOfType(Enums\StatementType::Join);
		
		if(!empty($stats))
		{
			foreach($stats as $stat)
            {
                $this->query .= $stat->toSql();
            }
		}
	}
    
    private function prepareUnionClause()
	{
		$stats = $this->SelectStatemementsOfType(Enums\StatementType::Union);
        
        if($this->union_flag)
        {
            foreach($stats as $stat)
            {
                $this->query .= $stat->toSql();
            }
        }
	}
	
	public function toSql()
	{
		$this->query .= "SELECT ";
		
		foreach($this->fields as $k => $field)
		{
			$this->query .= $field->toSql().(($k != count($this->fields) - 1) ? "," : "")./*"<br />\r\n".*/" ";
		}
		
		$this->prepareFromClause();
		$this->prepareJoinClause();
		$this->prepareWhereClause();
		$this->prepareOtherClauses();
        $this->prepareUnionClause();
		
		return trim($this->query);
	}
}
?>