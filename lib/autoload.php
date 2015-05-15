<?php
spl_autoload_register(function ($name) {
    
    if(preg_match("/^library\\\\/i", $name))
    {
        require_once
        (
            preg_replace("/library\\\\/i", "lib\\", $name).".php"
        );
    }
});
?>