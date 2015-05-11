<?php
namespace Library\Sql\QueryBuilder\QueryItems;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;

abstract class QueryItem
{
    public $name;
    public $alias;
    
    protected $has_alias;
    protected $is_query;
    
    public function __construct($string, $alias = null, $parameters = null)
    {
        if(!is_string($string) && UtilitiesService::GetParentClassName($string) != "BaseQuery")
        {
            throw new Excs\ArgumentException("Invalid table");
        }
        
        if(!is_null($alias) && !is_string($alias))
        {
            throw new Excs\ArgumentException("Alias must be a string");
        }
        
        if(UtilitiesService::GetParentClassName($string) == "BaseQuery")
        {
            $this->is_query = true;
        }
        else
        {
            $this->is_query = false;
        }
        
        if(is_null($alias) && !$this->is_query)
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
    
    protected function FindAlias($string, &$matches)
    {
        return preg_match("/^(.*?)\s+(?:as\s+)?(.*?)$/i", $string, $matches);
    }
    
    public function HasAlias()
    {
        return $this->has_alias;
    }
    
    public abstract function toSql();
}
?>