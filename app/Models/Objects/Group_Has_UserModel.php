<?php
namespace App\Models\Objects;
// This model is for containing the information for a job posting

class Group_Has_UserModel
{

    private $group_id;

    private $user_id;
    
    function __construct($group_id, $user_id)
    {
        $this->group_id = $group_id;
        $this->user_id = $user_id;
    }

    public function getGroup_id()
    {
        return $this->group_id;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setGroup_id($group_id)
    {
        $this->group_id = $group_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __toString()
    {
        return "Group_has_User| Group_id: " . $this->group_id . " User_id: " . $this->user_id;
    }

}