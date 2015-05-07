<?php
namespace Library\Collections;

use Library\Collections\Interfaces\ICollection;
use Library\Exceptions\ArgumentException;
use Library\Exceptions\IndexOutOfRangeException;

class Collection extends SimpleList implements ICollection
{
    public function __construct($initialItems = null)
    {
        parent::__construct($initialItems);
    }
    
    //ICollection implementation
    
    public function Each($callback)
    {
        $args = func_get_args();
        $callback = array_shift($args);
        
        $this->items = $this->ApplyCallback($callback, $args);
    }
    
    public function Filter($callback)
    {
        if(!is_callable($callback))
        {
            throw new ArgumentException("Invalid callback");
        }

        $collection = new Collection();
        
        foreach($this->items as $k => $v)
        {
            if($callback($k, $v))
            {
                $collection->Add($v);
            }
        }
        
        return $collection;
    }
    
    public function Map($callback)
    {
        $args = func_get_args();
        $callback = array_shift($args);
        
        return new Collection($this->ApplyCallback($callback, $args));
    }
    
    public function Range($size, $from = null)
    {
        if($size < 0 || $from < 0)
        {
            throw new ArgumentException("Size and starting index must be greater than zero");
        }
        
        if($size > count($this->items) || $from > count($this->items))
        {
            throw new IndexOutOfRangeException("Size and starting index are greater than the collection length");
        }
        
        if(($size + $from) > $this->Count())
        {
            throw new IndexOutOfRangeException("The range you're trying to extract is out of the collection range.");
        }
        
        $array = $this->items;
        
        if(!is_null($from))
        {
            $array = array_slice($this->items, $from);
        }
        
        return new Collection($this->slice($size, $array));
    }
    
    public function Shuffle()
    {
        shuffle($this->items);
        
        return $this;
    }
    
    //Private methods
    
    private function slice($size, $array)
    {
        return array_diff($array, array_slice($array, $size));
    }
    
    private function ApplyCallback($callback, $args)
    {
        if(!is_callable($callback))
        {
            throw new ArgumentException("Invalid callback");
        }
        
        $results = array();
        
        foreach($this->items as $k => $v)
        {
            $ret = call_user_func_array($callback, array_merge(array($k, $v), $args));
            $results[] = (is_null($ret)) ? $v : $ret;
        }
        
        return $results;
    }
}
?>
