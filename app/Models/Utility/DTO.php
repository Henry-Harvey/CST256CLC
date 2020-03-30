<?php
namespace App\Models\Utility;

// product class
class DTO implements \JsonSerializable
{

    private $errorCode;
    private $errorMessage;
    private $data;

    public function __construct($errorCode, $errorMessage, $data)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->data = $data;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    
    public function __toString()
    {
        if(is_array($this->data)){
            return "DTO| Eror Code: " . $this->errorCode . " Error Message: " . $this->errorMessage . " Data: " . implode($this->data);
        }
        return "DTO| Eror Code: " . $this->errorCode . " Error Message: " . $this->errorMessage . " Data: " . $this->data;
    }
       
}