<?php
namespace Library\Sql\QueryBuilder\Enums;

use Library\Exceptions as Excs;

abstract class JoinType extends \Library\BaseEnum
{
    const Inner = 0;
    const Left = 1;
    const Right = 2;
    
    public static function getKeyword($type)
    {
        if(!self::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        switch($type)
        {
            case self::Inner:
                return "INNER";
            break;
            case self::Left:
                return "LEFT";
            break;
            case self::Right:
                return "RIGHT";
            break;
        }
    }
}
?>