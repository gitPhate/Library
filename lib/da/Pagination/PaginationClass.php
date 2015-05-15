<?php
namespace Library\Sql\Pagination;

use Library\Utilities\UtilitiesService;
use Library\Exceptions\ArgumentException;

class PaginationClass
{
    private $_dataSource;
    
    public function __construct($dataSource)
    {
        if(UtilitiesService::GetClassName($dataSource) != "ResultSet")
        {
            throw new ArgumentException("The pagination tool is designed to work with an instance of the ResultSet class");
        }
        
        $this->_dataSource = $dataSource;
    }
}
?>