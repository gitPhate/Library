<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class GroupByStatement extends OtherStatement
{
    public function __construct($fields)
    {
        parent::__construct($fields);
        $this->keyword = "GROUP BY";
    }
    
    public function toSql()
    {
        $buffer = $this->keyword." ";
        foreach($this->params as $k => $field)
        {
            $buffer .= $field;
            if($k != count($this->params) - 1)
                $buffer .= ", ";
        }
        
        return $buffer;
    }
}
?>