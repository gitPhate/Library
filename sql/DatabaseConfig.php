<?php
namespace Library\Sql;

use Library\Exceptions as Excs;

final class DatabaseConfig
{
	public $Host;
	public $User;
	public $Password;
	public $Name;
	
	public function __construct($host, $user, $psw, $name)
	{
		if(!is_string($host))
		{
			throw new Excs\ArgumentException("Invalid host");
		}
		
		if(!is_string($user))
		{
			throw new Excs\ArgumentException("Invalid host");
		}
		
		if(!is_string($psw))
		{
			throw new Excs\ArgumentException("Invalid host");
		}
		
		if(!is_string($name))
		{
			throw new Excs\ArgumentException("Invalid host");
		}
		
		$this->Host = $host;
		$this->User = $user;
		$this->Password = $psw;
		$this->Name = $name;
	}
	
	public function CreateNewConnection()
	{
		return new \mysqli($this->Host, $this->User, $this->Password, $this->Name);
	}
}
?>