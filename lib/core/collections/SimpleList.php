<?php
namespace Library\Core\Collections;

use Library\Core\Collections\Interfaces\IBaseCollection;
use Library\Core\Collections\Interfaces\IList;
use Library\Core\Exceptions\ArgumentException;

class SimpleList extends AbstractCollection implements IList
{
    public function __construct($initialItems = null)
    {
        parent::__construct($initialItems);
    }
    
    //IList implementation
    
    public function Add($element)
    {
        $this->items[] = $element;
    }
    
    public function AddRange($elements)
    {
        if(!is_array($element))
        {
            throw new ArgumentException("Invalid elements");
        }
        
        $this->items = array_merge($element, $this->items);
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
    
    public function First()
    {
        return $this->items[0];
    }
    
    public function Remove($value)
    {
        $index = $this->search_item($value);
        
        if(is_numeric($index))
        {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            
            return true;
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