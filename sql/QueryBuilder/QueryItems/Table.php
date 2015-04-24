<?php
namespace Library\Sql\QueryBuilder\QueryItems;

class Table extends QueryItem
{
    public function __construct($string, $alias = null)
    {
        if(preg_match("/\s/", trim($string)))
        {
            $string = "(".$string.")";
        }
        
        parent::__construct($string, $alias);
    }
    
    protected function FindAlias($string, &$matches)
    {
        return preg_match("/^(.*?)\s+(?:as\s+)?(.*?)$/i", $string, $matches);
    }
    
    public function toSql()
    {
        $buffer = $this->name;
        
        if($this->has_alias)
        {
            $buffer .= " ".$this->alias;
        }
        
        return $buffer;
    }
}
?>