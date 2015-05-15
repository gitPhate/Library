<?php
namespace Library\Sql;

use Library\Exceptions as Excs;
use Library\Sql\QueryBuilder as QB;

class QueryBuilder
{
    public $table;
    
    public function __construct()
    {
        $table = null;
        return $this;
    }
    
    public function RawQuery($sql)
    {
        return new QB\RawQueryBuilder($sql);
    }
    
    public function Select($select, $alias = null)
    {
        $query = new QB\SelectQuery();
        return $query->Select($select, $alias);
    }
    
    public function SelectAll($table = null)
    {
        return $this->Select(((is_null($table)) ? "" : $table.".")."*", null);
    }
    
    public function SelectDistinct($select, $alias = null)
    {
        $query = new QB\SelectQuery();
        return $query->Select($select, $alias, true);
    }
    
    public function Insert($fields)
    {
        return new QB\InsertQuery($fields);
    }
    
    public function Update($table)
    {
        return new QB\UpdateQuery($table);
    }
    
    public function DeleteFrom($table)
    {
        $obj = new QB\DeleteQuery();
        return $obj->From($table);
    }
}
?>