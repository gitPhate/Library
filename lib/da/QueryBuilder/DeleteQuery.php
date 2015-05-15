<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;

class DeleteQuery extends BaseQuery
{
    public function __construct()
    {
        parent::__construct();
        
        return $this;
    }
    
    public function toSql()
    {
        $this->query = "DELETE ";
        $this->prepareFromClause();
        $this->prepareWhereClause();
        
        return $this->query;
    }
}