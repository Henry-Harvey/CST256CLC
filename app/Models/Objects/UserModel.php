<?php
namespace App\Models\Objects;
// This model is for containing the information for people who use the site

class UserModel implements \JsonSerializable
{

    private $id;

    private $first_name;

    private $last_name;

    private $location;

    private $summary;

    private $role;

    private $credentials_id;

    // not in the constructor
    private $credentials;

    function __construct($id, $first_name, $last_name, $location, $summary, $role, $credentials_id)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->location = $location;
        $this->summary = $summary;
        $this->role = $role;
        $this->credentials_id = $credentials_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getCredentials_id()
    {
        return $this->credentials_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setCredentials_id($credentials_id)
    {
        $this->credentials_id = $credentials_id;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    public function __toString()
    {
        if ($this->credentials != null) {
            return "User| ID: " . $this->id . " First Name: " . $this->first_name . " Last Name: " . $this->last_name . " Location: " . $this->location . " Summary: " . $this->summary . " Role: " . $this->role . " Credentials ID: " . $this->credentials_id . " " . $this->credentials;
        }
        return "User| ID: " . $this->id . "First Name: " . $this->first_name . " Last Name: " . $this->last_name . " Location: " . $this->location . " Summary: " . $this->summary . " Role: " . $this->role . " Credentials ID: " . $this->credentials_id;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}