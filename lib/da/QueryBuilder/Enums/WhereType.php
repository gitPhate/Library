<?php
namespace Library\Sql\QueryBuilder\Enums;

use Library\Exceptions as Excs;

abstract class WhereType extends \Library\Core\BaseEnum
{
    const First = 0;
    const AndWhere = 1;
    const OrWhere = 2;
    
    public static function getKeyword($type)
    {
        if(!self::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        return ($type == self::First) ? "" : (($type == self::AndWhere) ? "AND" : "OR");
    }
}
?>