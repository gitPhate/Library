<?php
namespace Library\Sql\QueryBuilder\Enums;

abstract class StatementType extends \Library\BaseEnum
{
    const Where = "where";
    const Join = "join";
    const Other = "other";
    const Update = "update";
    const Union = "union";
}
?>