<?php
namespace Library\Collections;

use Library\Collections\Interfaces\IDictionary;
use Library\Exceptions\ArgumentException;

class Dictionary extends AbstractCollection implements IDictionary
{
    public function __construct()
    {
        parent::__construct();
    }
    
    //IDictionary implementation
    
    public function Add($key, $element)
    {
        if(is_null($key))
        {
            throw new ArgumentNullException("Given key is null");
        }
        
        if($this->ContainsKey($key))
        {
           throw new ArgumentException("An element with the same key already exists in the dictionary");
        }
        
        $this->items[$key] = $element;
    }
    
    public function ContainsKey($key)
    {
        if(is_null($key))
        {
            throw new ArgumentNullException("Given key is null");
        }
        
        return array_key_exists($key, $this->items);
    }
    
    public function Keys()
    {
        return new Collection(array_keys($this->items));
    }
    
    public function Values()
    {
        return new Collection(array_values($this->items));
    }
    
    //Inherited abstract methods
    
    public function First()
    {
        $array = $this->items;
        $value = reset($array);
        
        return new Tuple(key($array), $value);
    }
    
    public function Remove($value)
    {
        if(is_null($value))
        {
            throw new ArgumentNullException("Given key is null");
        }
        
        unset($this->items[$value]);
        
        return true;
    }
    
    public function ToArray()
    {
        return $this->items;
    }
    
    public function ToCollection()
    {
        return $this->values();
    }
}
?>