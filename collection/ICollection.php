<?php
namespace Library\Collection;

interface ICollection
{
    public function each(callable $c);
    public function map(callable $c);
    public function filter(callable $c);
    public function first();
    public function contains($value);
    public function shuffle();
    public function range($position, $offset);
    public function any();
    public function toArray(); //preserve keys
    public function toList(); //drop keys and assign new numeric ones
}
?>