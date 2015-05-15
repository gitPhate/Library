<?php
namespace Library\Core\Collections\Interfaces;

interface IDictionary
{
    public function Add($key, $element);
    public function ContainsKey($element);
    public function Keys();
    public function Values();
}
?>