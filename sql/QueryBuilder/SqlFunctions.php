<?php
namespace Library\Sql\QueryBuilder;

use Library\Utilities\UtilitiesService;
use Library\Exceptions as Excs;

class SqlFunctions
{
    public static function Coalesce()
    {
        return "COALESCE("
            .implode
            (
                ",",
                func_get_args()
            )
        .")";
    }
    
    
}