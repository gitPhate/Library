<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class LimitStatement extends OtherStatement
{   
    public function __construct($fields)
    {
        if(!is_array($fields) && count($fields) != 2)
        {
            throw new Excs\ArgumentException("Invalid fields");
        }
        
        parent::__construct($fields);
        
        $this->keyword = "LIMIT";
    }
    
    public function toSql()
    {
        $num = $this->params[0];
        $offset = $this->params[1];
        
        $buffer = $this->keyword." ".$num;
        
        if(!is_null($offset))
        {
            $buffer .= ", {$offset}";
        }
        
        return $buffer;
    }
}
?>