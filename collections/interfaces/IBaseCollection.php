<?php
namespace Library\Collections\Interfaces;

interface IBaseCollection
{
    public function Any();
    public function Clear();
    public function Count();
    public function First();
    public function Remove($value);
    public function ToArray();
    public function ToCollection();
}
?>