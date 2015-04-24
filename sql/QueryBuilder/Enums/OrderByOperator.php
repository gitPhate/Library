<?php
namespace Library\Sql\QueryBuilder\Enums;

use Library\Exceptions as Excs;

abstract class OrderByOperator extends \Library\BaseEnum
{
    const Asc = 0;
    const Desc = 1;
    
    public static function getKeyword($type)
    {
        if(!self::isValidValue($type))
        {
            throw new Excs\ArgumentException("Invalid type");
        }
        
        return ($type == self::Asc) ? "ASC" : "DESC";
    }
}
?>