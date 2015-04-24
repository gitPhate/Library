<?php
namespace Library\Sql\QueryBuilder;
use Library\Exceptions as Excs;

class Builder
{
    public $table;
    
    public function __construct()
    {
        $table = null;
        return $this;
    }
    
    public function RawQuery($sql)
    {
        return new RawQueryBuilder($sql);
    }
    
    public function Select($select, $alias = null)
    {
        $query = new SelectQuery();
        return $query->Select($select, $alias);
    }
    
    public function SelectAll($table = null)
    {
        return $this->Select(((is_null($table)) ? "" : $table.".")."*", null);
    }
    
    public function SelectDistinct($select, $alias = null)
    {
        $query = new SelectQuery();
        return $query->Select($select, $alias, true);
    }
    
    public function Insert($fields)
    {
        return new InsertQuery($fields);
    }
    
    public function Update($table)
    {
        return new UpdateQuery($table);
    }
    
    public function DeleteFrom($table)
    {
        $obj = new DeleteQuery();
        return $obj->From($table);
    }
}
?>