<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Exceptions as Excs;
use Library\Sql\QueryBuilder\Enums;

abstract class BaseStatement
{
    public $type;
    protected $params;
    
    public function __construct($type, $params)
    {
        if(!Enums\StatementType::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        if(!is_array($params))
        {
            throw new Excs\ArgumentException("Invalid params");
        }
        
        $this->type = $type;
        $this->params = $params;
    }
    
    public abstract function toSql();
}