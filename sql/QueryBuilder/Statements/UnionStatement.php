<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\Enums;
use Library\Utilities\UtilitiesService;

class UnionStatement extends BaseStatement
{
    public $all_flag;
    
    public function __construct($query, $all)
    {
        if(!UtilitiesService::GetClassName($query))
        {
            throw new \Library\Exceptions\ArgumentException("Invalid query. A query must be an instance of SelectQuery");
        }
        
        if(!is_bool($all))
        {
            throw new \Library\Exceptions\ArgumentException("Invalid all param");
        }
        
        parent::__construct(Enums\StatementType::Union, array($query));
        
        $this->all_flag = $all;
    }
    
    public function toSql()
    {
        $buffer = 
            "UNION "
            .(($this->all_flag) ? "ALL" : "")
            ." "
            .$this
                ->params[0]
                ->toSql();
        return $buffer;
    }
}
?>