<?php
function autoload($name)
{
    //echo "loading {$name}.php<br />";
    
    if(preg_match("/^library\\\\/i", $name))
    {
        require_once($name.".php");
    }
}

spl_autoload_register("autoload");
?>