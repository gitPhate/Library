<?php
namespace Library\Collections;

interface IList
{
    public function Each($callback, $param);
    public function Filter($callback);
    public function Map($callback, $param);
    public function Range($size, $from);
    public function Shuffle();
}
?>