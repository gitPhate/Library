<?php
namespace Library;

use Library\Exceptions as Excs;
use Library\Utilities\CallbackManager;

class Lazy
{
    private $manager;
    private $object;
    private $is_class;
    private $is_callback;
    
    public function __construct()
    {
        $args = func_get_args();
        $object = array_shift($args);
        
        $this->is_callback = false;
        $this->is_class = false;
        
        if(is_callable($object))
        {
            $this->is_callback = true;
            $this->manager = new CallbackManager($object, $args);
        }
        elseif(class_exists($object))
        {
            $this->is_class = true;
            $this->object = $object;
        }
        else
        {
            throw new ArgumentException("Argument is neither a callback nor a valid class");
        }
    }
    
    public function Value()
    {
        if($this->is_callback)
            return $this->manager->Call();
        else
            return new $this->object();
    }
}
?>