<?php
namespace Library\Sql\QueryBuilder\Enums;

use Library\Exceptions as Excs;

abstract class JoinConditionType extends \Library\BaseEnum
{
    const AndOn = 0;
    const OrOn = 1;
    const First = 2;
    
    public static function getKeyword($type)
    {
        if(!self::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        return ($type == self::First) ? "ON" : (($type == self::AndOn) ? "AND" : "OR");
    }
}
?>