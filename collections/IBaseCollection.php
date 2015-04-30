<?php
namespace Library\Collections;

interface IBaseCollection
{
    public function Add($element);
    public function Any();
    public function Clear();
    public function Contains($element);
    public function First();
    public function Remove($element);
    public function ToArray();
}
?>