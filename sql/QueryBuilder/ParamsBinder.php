<?php
namespace Library\Sql\QueryBuilder;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;

class ParamsBinder
{
    public static function BindSingleParam($matches, $value)
    {
        if(!is_array($matches))
        {
            throw new Excs\ArgumentException("Invalid condition");
        }
        
        if(!is_numeric($value) && !is_string($value))
        {
            throw new Excs\ArgumentException("Invalid value");
        }
        
        foreach($matches as $k => $match)
        {
            $regex = ConditionParser::$question_mark_regex;
            if(preg_match($regex, $match))
            {
                $matches[$k] = preg_replace($regex, UtilitiesService::WrapInQuotes($value), $match);
            }
        }
        
        return $matches;
    }
    
    public static function BindParams($matches, $values)
    {
        if(!is_array($matches))
        {
            throw new Excs\ArgumentException("Invalid condition");
        }
        
        if(!is_array($values))
        {
            throw new Excs\ArgumentException("Invalid values");
        }
        
        foreach($matches as $k => $match)
        {
            if(preg_match(ConditionParser::$colon_regex, $match))
            {
                if(array_key_exists($match, $values))
                {
                    $matches[$k] = UtilitiesService::WrapInQuotes($values[$match]);
                }
                else
                {
                    throw new Excs\InvalidOperationException("param not found for {$match}");
                }
            }
        }
        
        return $matches;
    }
}