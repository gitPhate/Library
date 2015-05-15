<?php
namespace Library\Exceptions;

class LibraryException extends \Exception
{
	protected $class;
    
    public function __construct($class, $message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
		$this->class = $class;
    }
	
	public function __toString()
    {
        $string = $this->class.": ".$this->message."\nat ";
        
		foreach($this->getTrace() as $k => $level)
		{
			$string .= $level["class"].".".$level["function"]."(".basename($level["file"]).":".$level["line"].")\n";
		}
        
		return $string;
    }
}
?>