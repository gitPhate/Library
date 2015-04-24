<?php
namespace Library\Utilities;

use Library\Exceptions as Excs;

final class CallbackManager
{
	public $Callback;
	public $Params;
	
	public function __construct($callback, $params = array())
	{
		if(!is_callable($callback))
		{
			throw new Excs\ArgumentException("Provided function is not callable");
		}
		
		if(!is_array($params))
		{
			throw new Excs\ArgumentException("Params must be an array");
		}
		
		$this->Callback = $callback;
		$this->Params = $params;
	}
	
	public function Call()
	{
		if(empty($this->Params))
			$this->Params = func_get_args();
		
		return call_user_func_array($this->Callback, $this->Params);
	}
}

?>