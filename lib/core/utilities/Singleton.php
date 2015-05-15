<?php
namespace Library\Core\Utilities;

abstract class Singleton
{
    public static $Instance;
    
    public static function GetInstance() {

        if(!self::$Instance) {
            $refl = new \ReflectionClass(get_called_class());
            self::$Instance = $refl->newInstanceArgs(func_get_args());
        }
        
        return self::$Instance;
    }
}
?>