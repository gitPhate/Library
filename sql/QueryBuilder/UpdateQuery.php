<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;

class UpdateQuery extends BaseQuery
{
    public function __construct($table)
    {
        parent::__construct();
        
        $this->table = new QueryItems\Table($table);
        
        return $this;
    }
    
    public function Set()
    {
        $args = func_get_args();
        $fields = array();
        
        if(count($args) == 1)
        {
            if(!is_array($args[0]))
            {
                $args[0] = array($args[0]);
            }
            
            foreach($args[0] as $k => $v)
            {
                $fields[] = new QueryItems\UpdatePair($k, $v);
            }
        }
        elseif(count($args) == 2)
        {
            $fields = new QueryItems\UpdatePair($args[0], $args[1]);
        }
        else
        {
            throw new Excs\ArgumentException("Invalid fields");
        }
        
        $this->statements[] = new Statements\UpdateStatement($fields);
        
        return $this;
    }
    
    public function toSql()
    {
        $this->query =
            "UPDATE "
            .$this
                ->table
                ->toSql()
            ." "
            .$this
                ->statements[0]
                ->toSql();
        
        $this->prepareWhereClause();
        
        return trim($this->query);
    }
}
?>