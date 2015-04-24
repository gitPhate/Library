<?php
namespace Library\Sql\QueryBuilder;

class AggregateFunctions
{
    public static $AggregateFunctionsList = array("sum", "count", "avg", "min", "max");
    
    public static function Sum($column, $alias = null)
    {
        return self::aggregate("SUM", $column, $alias);
    }
    public static function SumDistinct($column, $alias = null)
    {
        return self::aggregate("SUM", $column, $alias, true);
    }
    
    public static function Count($column, $alias = null)
    {
        return self::aggregate("COUNT", $column, $alias);
    }
    public static function CountAll($alias = null)
    {
        return self::aggregate("COUNT", "*", $alias);
    }
    public static function CountDistinct($column, $alias = null)
    {
        return self::aggregate("COUNT", $column, $alias, true);
    }
    
    public static function Avg($column, $alias = null)
    {
        return self::aggregate("AVG", $column, $alias);
    }
    public static function AvgDistinct($column, $alias = null)
    {
        return self::aggregate("AVG", $column, $alias, true);
    }
    
    public static function Min($column, $alias = null)
    {
        return self::aggregate("MIN", $column, $alias);
    }
    public static function MinDistinct($column, $alias = null)
    {
        return self::aggregate("MIN", $column, $alias, true);
    }
    
    public static function Max($column, $alias = null)
    {
        return self::aggregate("MAX", $column, $alias);
    }
    public static function MaxDistinct($column, $alias = null)
    {
        return self::aggregate("MAX", $column, $alias, true);
    }
    
    private static function aggregate($keyword, $column, $alias, $distinct = false)
    {
        return $keyword."(".(($distinct) ? "DISTINCT " : "").$column.")".((!is_null($alias)) ? " AS ".$alias : "");
    }
}


?>