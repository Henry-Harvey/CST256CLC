<?php
/**
 * Model | app/Models/Utility/DTO.php
 * Model for holding data transfer object information
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Utility;

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