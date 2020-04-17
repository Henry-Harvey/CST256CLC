<?php
/**
 * Model | app/Models/Utility/SecurityPrinciple.php
 * Model for holding useful informatin to store in the session
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Utility;
// This class contains the information that is stored in the session

class SecurityPrinciple
{
    private $user_id;
    
    private $first_name;
    
    private $role;
    
    function __construct($user_id, $first_name, $role)
    {
        $this->user_id = $user_id;
        $this->first_name = $first_name;
        $this->role = $role;
    }
    
    public function getUser_id()
    {
        return $this->user_id;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

}

