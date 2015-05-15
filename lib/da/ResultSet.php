<?php
namespace Library\Sql;

use Library\Exceptions as Excs;
use Library\Utilities\UtilitiesService;
use Library\Collections\Collection;

define("FETCH_NUM", MYSQLI_NUM);
define("FETCH_ASSOC", MYSQLI_ASSOC);
define("FETCH_BOTH", MYSQLI_BOTH);

class ResultSet
{
    private $results;
    
    public function __construct($result)
    {
        if(!UtilitiesService::GetClassName($result) == "mysqli_result")
        {
            throw new Excs\ArgumentException("Invalid result source");
        }
        
        $this->results = $result;
    }
    
    public function FetchArray($resultType = FETCH_BOTH)
    {
        return $this->results->fetch_array($resultType);
    }
    
    public function FetchObject()
    {
        return $this->results->fetch_object();
    }
    
    public function FetchAll()
    {
        $args = func_get_args();
        
        $resultType = FETCH_BOTH;
        $callback = null;
        
        if(isset($args[0]))
        {
            if(is_callable($args[0]))
            {
                $callback = $args[0];
                
                if(isset($args[1]))
                {
                    if(is_numeric($args[1]))
                    {
                        $resultType = $args[1];
                    }
                    else
                    {
                        throw new ArgumentException("Invalid fetch mode");
                    }
                }
                else
                {
                    $resultType = FETCH_BOTH;
                }
            }
            elseif(is_numeric($args[0]))
            {
                $resultType = $args[0];
            }
            else
            {
                throw new ArgumentException("Invalid fetch mode");
            }
        }
        
        $list = new Collection();
        
        while($row = $this->FetchArray($resultType))
        {
            $list->Add($row);
        }
        
        if(!is_null($callback))
        {
            $list->Each($callback);
        }
        
        return $list;
    }
    
    public function FetchAllObject()
    {
        $list = new Collection();
        
        while($row = $this->FetchObject())
        {
            $list->Add($row);
        }
        
        return $list;
    }
    
    public function Close()
    {
        $this->results->close();
    }
    
    public function Free()
    {
        $this->Close();
    }
}

?>