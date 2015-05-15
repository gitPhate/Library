<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Sql\QueryBuilder\Enums;
use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class UpdateStatement extends BaseStatement
{   
    public function __construct($pairs)
    {
        if(!is_array($pairs) && UtilitiesService::GetClassName($pairs) != "UpdatePair")
        {
            throw new Excs\ArgumentException("Invalid fields");
        }
        
        if(!is_array($pairs))
        {
            $pairs = array($pairs);
        }
        
        parent::__construct(Enums\StatementType::Update, $pairs);
    }
    
    public function toSql()
    {
        $buffer = "SET ";
        
        foreach($this->params as $pair)
        {
            $buffer .= $pair->toSql();
            
            if($pair != end($this->params))
            {
                $buffer .= ", ";
            }
        }
        
        return $buffer." ";
    }
}
?>