<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;

class ConditionParser
{
    public static $normal_regex = null;
    public static $like_regex = "/^(.+?)\s+(LIKE)(?:\s+(%(?:\?)|(?:\?)%|%(?:\?)%)?)$/i";
    public static $between_regex = "/^(.+?)\s+(BETWEEN)\s+(.+?)\s+(?:AND)\s+(.+?)$/i";
    public static $aggr_function_regex = null;
    public static $question_mark_regex = "/\?/";
    public static $colon_regex = "/^(\:.+)$/i";
    public static $asc_desc_regex = "/^(.+?)(?:\s+(ASC|DESC))?$/i";
    public static $join_regex = null;
    
    public static function ParseWhereClause($condition)
    {
        if(!is_string($condition))
        {
            throw new Excs\ArgumentException("Invalid condition");
        }
        
        $matches = array();
        
        if(empty($matches))
        {
            preg_match(self::$like_regex, $condition, $matches);
            
            if(empty($matches))
            {
                preg_match(self::$between_regex, $condition, $matches);
            }
        }
        
        if(!empty($matches))
        {
            array_shift($matches);
        }
        
        $sanitizedArray = self::SanitizeArray($matches);
        
        return $sanitizedArray;
    }
    
    private static function ParseConditionByRegex($regex, $cond)
    {
        $matches = array();
        
        if(!is_string($cond))
        {
            throw new Excs\ArgumentException("Invalid condition");
        }
        if(!is_string($regex))
        {
            throw new Excs\ArgumentException("Invalid regex");
        }
        
        if(preg_match($regex, $cond, $matches))
        {
            array_shift($matches);
        }
        
        return self::SanitizeArray($matches);
    }
    
    public static function ParseHavingClause($function, $condition)
    {
        preg_match(self::$aggr_function_regex, $function, $aggr_matches);
        array_shift($aggr_matches);
        preg_match(self::$normal_regex, $function." ".$condition, $cond_matches);
        array_shift($cond_matches);
        array_shift($cond_matches);
        
        if(empty($cond_matches) || empty($aggr_matches))
        {
            return array();
        }
        
        return array_merge($aggr_matches, $cond_matches);
    }
    
    public static function ParseJoinOnClause($condition)
    {
        $array = self::ParseConditionByRegex(self::$join_regex, $condition);
        
        if(empty($array[3]) && !empty($array[5]))
        {
            $array[3] = $array[5];
            unset($array[4]);
            unset($array[5]);
        }
        
        return $array;
    }
    
    public static function RegexInitialization()
    {
        self::$normal_regex = "/^(.+?)\s+(".implode("|", BaseQuery::$PrimaryOperators).")(?:\s+(\?))$/i";
        self::$aggr_function_regex = "/^(".implode("|", AggregateFunctions::$AggregateFunctionsList).")\((.*?)\)$/i";   
        self::$join_regex = "/^(.+?)\.(.+?)\s+(".implode("|", BaseQuery::$PrimaryOperators).")\s+(?:(.+?)\.(.+?)|(\?))$/i";
    }
    
    public static function SanitizeArray($array)
    {
        if(!is_array($array))
        {
            throw new Excs\ArgumentException("Invalid array");
        }
        
        if(get_magic_quotes_gpc()) 
        {
            $array = array_map('stripslashes', $array);
        }
        
        return array_map(array("Library\Sql\QueryBuilder\ConditionParser", "SanitizeString"), $array);
    }
    
    public static function SanitizeString($string)
    {
        return addslashes($string);
    }
}

ConditionParser::RegexInitialization();
?>