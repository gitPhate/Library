<?php
namespace Library\Collections;

use Library\Collections\Interfaces\IBaseCollection;
use Library\Exceptions\ArgumentException;

abstract class AbstractCollection implements IBaseCollection, \ArrayAccess
{
    protected $items;
    
    public function __construct($initialItems = null)
    {
        if(!is_null($initialItems))
        {
            if(!is_array($initialItems))
            {
                throw new ArgumentException("Argument must be an array");
            }
            
            $this->items = $initialItems;
        }
        else
        {
            $this->Clear();
        }
    }
    
    //ICollection implementation
    
    public function Any()
    {
        return empty($this->items);
    }
    
    public function Clear()
    {
        $this->items = array();
    }
    
    public function Count()
    {
        return count($this->items);
    }
    
    public function First()
    {
        return $this->items[0];
    }
    
    public abstract function Remove($value);
    
    public abstract function ToArray();
    
    public abstract function ToCollection();
    
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