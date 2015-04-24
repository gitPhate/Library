<?php
namespace Library\Sql\QueryBuilder\Statements;

use Library\Sql\QueryBuilder\BaseQuery;
use Library\Utilities\UtilitiesService;
use Library\Sql\QueryBuilder\Enums;
use Library\Exceptions as Excs;

abstract class OtherStatement extends BaseStatement
{
    protected $keyword;
    
    public function __construct($fields)
    {
        parent::__construct(Enums\StatementType::Other, is_string($fields) ? array($fields) : $fields);
    }
    
    public function getKeyword()
    {
        return $this->keyword;
    }
}
?>