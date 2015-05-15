<?php
namespace Library\Utilities;

class UtilitiesService
{
	public static function GetClassName($obj)
	{
		return basename(get_class($obj));
	}
	
	public static function GetParentClassName($obj)
	{
		return basename(get_parent_class($obj));
	}
	
	public static function WrapInQuotes($obj)
	{
		return (is_numeric($obj)) ? $obj : "'{$obj}'";
	}
	
	public static function IsAssoc($array) {
		
		if(!is_array($array))
		{
			throw new Exceptions\ArgumentException("parameter must be an array");
		}
		
		return
		(bool) count
		(
			array_filter
			(
				array_keys($array),
				'is_string'
			)
		);
	}
    
    static function AreAllStrings($array)
    {
        $filter =
            array_filter
            (
                $array,
                function($f)
                {
                    return !is_string($f);
                }
            );
        
        return empty($filter) ? true : false;
    }
}
?>