<?php
namespace Library\Collections;

use Library\Exceptions as Excs;

class SimpleList implements IBaseCollection, \ArrayAccess
{
    protected $items;
    
    public function __construct($initialItems = null)
    {
        if(!is_null($initialItems))
        {
            if(!is_array($initialItems))
            {
                throw new Excs\ArgumentException("Argument must be an array");
            }
            
            $this->items = $initialItems;
        }
        else
        {
            $this->Clear();
        }
    }
    
    //ICollection implementation
    
    public function Add($element)
    {
        if(is_array($element))
        {
            $this->items = array_merge($element, $this->items);
        }
        else
        {
            $this->items[] = $element;
        }
    }
    
    public function Any()
    {
        return empty($this->items);
    }
    
    public function Clear()
    {
        $this->items = array();
    }
    
    public function Contains($element)
    {
        if(is_numeric($this->search_item($element)))
        {
            return true;
        }
        
        return false;
    }
    
    public function First()
    {
        return $this->items[0];
    }
    
    public function Remove($element)
    {
        $index = $this->search_item($element);
        
        if(is_numeric($index))
        {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
        else
        {
            return false;
        }
    }
    
    public function ToArray()
    {
        return $this->items;
    }
    
    // ArrayAccess implementation
    
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->items[] = $value;
        }
        else
        {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
    
    //Private methods
    
    private function search_item($item)
    {
        return array_search($item, $this->items, true);
    }
}
?>