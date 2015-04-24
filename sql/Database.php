<?php
namespace Library\Sql;
use Library\Exceptions as Excs;

final class Database
{
	public $Config;
	public $AffectedRows;
	public $ClientInfo;
	public $ClientVersion;
	public $ConnectErrno;
	public $ConnectError;
	public $Errno;
	public $ErrorList;
	public $Error;
	public $FieldCount;
	public $HostInfo;
	public $ProtocolVersion;
	public $ServerInfo;
	public $ServerVersion;
	public $Info;
	public $InsertId;
	public $SqlState;
	public $ThreadId;
	public $WarningCount;
	
	private $db;
	
	public function __construct($config)
	{
		if(getClassName($config) != "DatabaseConfig")
		{
			throw new Excs\ArgumentException("Invalid configuration object");
		}
		
		$this->Config = $config;
	}
	
	public function Connect()
	{
		$this->db = $this->Config->CreateNewConnection();
		
		$this->SetPropsForOperation();
		
		$this->ClientInfo = $this->db->client_info;
		$this->ClientVersion = $this->db->client_version;
		$this->ConnectErrno = $this->db->connect_errno;
		$this->ConnectError = $this->db->connect_error;
		$this->FieldCount = $this->db->field_count;
		$this->HostInfo = $this->db->host_info;
		$this->ServerVersion = $this->db->server_version;
		$this->ServerInfo = $this->db->server_info;
		$this->ProtocolVersion = $this->db->protocol_version;
		$this->ThreadId = $this->db->thread_id;
	}
	
	public function Close()
	{
		$this->db->close();
	}
	
	public function Query($query)
	{
		if(basename(get_parent_class($query)) == "BaseQuery")
		{
			$query = $query->__toString();
		}
		
		$result = $this->db->query($query);
		$this->SetPropsForOperation();
		$this->AffectedRows = $this->db->affected_rows;
		$this->Info = $this->db->info;
		$this->InsertId = $this->db->insert_id;
		$this->WarningCount = $this->db->warning_count;
		
		return new ResultSet($result);
	}
	
	private function SetPropsForOperation()
	{
		$this->SqlState = $this->db->sqlstate;
		$this->Errno = $this->db->errno;
		$this->Error = $this->db->error;
		$this->ErrorList = $this->db->error_list;
	}
}