<?php
namespace Library\Sql\QueryBuilder;
use Library\Exceptions as Excs;

class RawQueryBuilder extends BaseQuery
{
    public function __construct($sql)
    {
        parent::__construct();
        
        if(!is_string($sql))
        {
            throw new Excs\ArgumentException("Invalid sql");
        }
        
        $this->raw_query_flag = true;
        
        parent::RawQuery($sql);
    }
    
    public function toSql()
    {
        if(!$this->raw_query_flag)
        {
            throw new Excs\InvalidOperationException("Can't call RawQuery() in this context");
        }
        
        return $this->query;
    }
}