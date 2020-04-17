<?php
/**
 * Model | app/Models/Objects/CredentialsModel.php
 * Model for holding user login information
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Objects;
//This model is for containing the information for people to log in to the site

class CredentialsModel implements \JsonSerializable
{

    private $id;

    private $username;

    private $password;

    private $suspended;
    
    function __construct($id, $username, $password, $suspended)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->suspended = $suspended;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSuspended()
    {
        return $this->suspended;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    }
    
    public function __toString()
    {
        return "Credentials| ID: " . $this->id . " Username: " . $this->username . " Password: " . $this->password . " Suspended: " . $this->suspended;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}