<?php
namespace Library\Core\Collections\Interfaces;

interface IBaseCollection
{
    public function Any();
    public function Clear();
    public function Count();
    public function First();
    public function IndexOf($element);
    public function Remove($value);
    public function ToArray();
    public function ToCollection();
}
?>