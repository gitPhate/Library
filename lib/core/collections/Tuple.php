<?php
namespace Library\Core\Collections;

use Library\Core\Exceptions\ArgumentException;

class Tuple
{
	public $Item1;
	public $Item2;
	public $Item3;
	public $Item4;
	public $Item5;
	public $Item6;
	public $Item7;
	public $Rest;
	
	public function __construct()
	{
		$args = func_get_args();
		
		if(count($args) > 8)
		{
			throw new ArgumentException("Elements in a tuple are at most 8");
		}
		
		if(count($args) == 0)
		{
			throw new ArgumentException("There must be at least one element in the tuple");
		}
		
		for($i = 1;$i <= count($args); $i++)
		{
			$fieldName = "Item".$i;
			$this->$fieldName = $args[$i - 1];
		}
	}
}
?>
