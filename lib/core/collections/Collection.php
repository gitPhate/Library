<?php
namespace Library\Core\Collections;

use Library\Core\Collections\Interfaces\ICollection;
use Library\Core\Collections\FilterMode;
use Library\Core\Exceptions\ArgumentException;
use Library\Core\Exceptions\IndexOutOfRangeException;
use Library\Core\Utilities\UtilitiesService;


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
    
    public function Filter($callback, $mode = FilterMode::Values)
    {
        if(!is_callable($callback))
        {
            throw new ArgumentException("Invalid callback");
        }
        
        if(!FilterMode::isValidValue($mode))
        {
            throw new ArgumentException("Invalid filter mode");
        }

        $collection = new Collection();
        
        switch($mode)
        {
            case FilterMode::Keys:
                foreach($this->items as $k => $v)
                {
                    if($callback($k))
                    {
                        $collection->Add($v);
                    }
                }
            break;
            case FilterMode::Values:
                foreach($this->items as $v)
                {
                    if($callback($v))
                    {
                        $collection->Add($v);
                    }
                }
            break;
            case FilterMode::Both:
                foreach($this->items as $k => $v)
                {
                    if($callback($k, $v))
                    {
                        $collection->Add($v);
                    }
                }
            break;
        }
        
        return $collection;
    }
    
    public function Map($callback)
    {
        $args = func_get_args();
        $callback = array_shift($args);
        
        $results = $this->ApplyCallback($callback, $args);
        
        if(empty(array_diff($results, $this->items)))
        {
            throw new ArgumentException("The callback does not have a return value");
        }
        
        return new Collection();
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
        
        foreach($this->items as $v)
        {
            $ret = call_user_func_array($callback, array_merge(array($v), $args));
            
            $results[] = (is_null($ret)) ? $v : $ret;
        }
        
        return $results;
    }
}
?>
