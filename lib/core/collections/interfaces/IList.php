<?php
namespace Library\Core\Collections\Interfaces;

interface IList
{
    public function Add($element);
    public function AddRange($elements);
    public function Contains($element);
}
?>