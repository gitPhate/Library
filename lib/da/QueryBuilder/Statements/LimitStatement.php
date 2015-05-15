<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class LimitStatement extends OtherStatement
{   
    public function __construct($offset, $row_count)
    {
        if(!is_numeric($offset) || !is_numeric($row_count) || $offset < 0 || $row_count < 0)
        {
            throw new Excs\ArgumentException("Invalid arguments");
        }
        
        parent::__construct([$offset, $row_count]);
        
        $this->keyword = "LIMIT";
    }
    
    public function toSql()
    {
        $offset = $this->params[0];
        $row_count = $this->params[1];
        
        $buffer = $this->keyword." ";
        
        if($offset != 0)
        {
            $buffer .= "{$offset}, ";
        }
        
        $buffer .= $row_count;
        
        return $buffer;
    }
}
?>