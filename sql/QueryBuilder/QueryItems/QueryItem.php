<?php
namespace Library\Sql\QueryBuilder\QueryItems;

use Library\Exceptions as Excs;

abstract class QueryItem
{
    public $name;
    public $alias;
    
    protected $has_alias;
    
    public function __construct($string, $alias = null)
    {
        if(!is_string($string))
        {
            throw new Excs\ArgumentException("Table must be a string");
        }
        
        if(!is_null($alias) && !is_string($alias))
        {
            throw new Excs\ArgumentException("Alias must be a string");
        }
        
        if(is_null($alias))
        {
            if($this->FindAlias($string, $matches))
            {
                $this->name = $matches[1];
                $this->alias = $matches[2];
                $this->has_alias = true;
            }
            else
            {
                $this->name = $string;
                $this->alias = null;
                $this->has_alias = false;
            }
        }
        else
        {
            $this->name = $string;
            $this->alias = $alias;
            $this->has_alias = true;
        }
    }
    
    public function HasAlias()
    {
        return $this->has_alias;
    }
    
    protected abstract function FindAlias($string, &$matches);
    public abstract function toSql();
}
?>