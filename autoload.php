<?php
function autoload($name)
{
    //echo "loading {$name}.php<br />";
    require_once($name.".php");
}

spl_autoload_register("autoload");
?>