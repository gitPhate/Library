<?php
namespace Library\Collections;

use Library\Collections\Interfaces\IBaseCollection;
use Library\Collections\Interfaces\IList;
use Library\Exceptions\ArgumentException;

class SimpleList extends AbstractCollection implements IList
{
    public function __construct($initialItems = null)
    {
        parent::__construct($initialItems);
    }
    
    //IList implementation
    
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
    
    public function Contains($element)
    {
        if(is_numeric($this->search_item($element)))
        {
            return true;
        }
        
        return false;
    }
    
    //Inherited abstract methods
    
    public function Remove($value)
    {
        $index = $this->search_item($value);
        
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
    
    public function ToCollection()
    {
        return new Collection($this->items);
    }
}
?>