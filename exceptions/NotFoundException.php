<?php
namespace Library\Exceptions;

class NotFoundException extends LibraryException
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct(__CLASS__, $message, $code, $previous);
    }
}
?>