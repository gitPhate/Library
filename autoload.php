<?php
spl_autoload_register(function ($name) {
    //echo "loading {$name}.php<br />";
    
    if(preg_match("/^library\\\\/i", $name))
    {
        require_once($name.".php");
    }
});
?>