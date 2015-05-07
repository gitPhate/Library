<?php
namespace Library\Collections\Interfaces;

interface ICollection
{
    public function Each($callback);
    public function Filter($callback, $mode);
    public function Map($callback);
    public function Range($size, $from);
    public function Shuffle();
}
?>