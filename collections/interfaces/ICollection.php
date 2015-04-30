<?php
namespace Library\Collections\Interfaces;

interface ICollection
{
    public function Each($callback, $param);
    public function Filter($callback);
    public function Map($callback, $param);
    public function Range($size, $from);
    public function Shuffle();
}
?>