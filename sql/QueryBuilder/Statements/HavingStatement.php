<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class HavingStatement extends OtherStatement
{
    public $function;
    public $operator;
    
    public function __construct($fields)
    {
        if(!is_array($fields) && count($fields) != 4)
        {
            throw new Excs\ArgumentException("Invalid fields");
        }
        
        $this->function = $fields[0];
        $this->operator = $fields[2];
        
        parent::__construct(array($fields[1], $fields[3]));
        
        $this->keyword = "HAVING";
    }
    
    public function toSql()
    {
        $buffer = $this->keyword." ";
        $buffer .= strtoupper($this->function) . "(" . $this->params[0] . ") " . $this->operator . " ". $this->params[1];
        
        return $buffer;
    }
}
?>