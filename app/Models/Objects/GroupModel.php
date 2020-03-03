<?php
namespace App\Models\Objects;
// This model is for containing the information for a job posting

class GroupModel
{

    private $id;

    private $title;
    
    private $description;
    
    private $owner_id;
    
    // array of users not in constructor
    private $members_array;
    
    function __construct($id, $title, $description, $owner_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->owner_id = $owner_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getOwner_id()
    {
        return $this->owner_id;
    }

    public function getMembers_array()
    {
        return $this->members_array;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setOwner_id($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    public function setMembers_array($members_array)
    {
        $this->members_array = $members_array;
    }

    public function __toString()
    {
        if($this->members_array != null){
            return "Group| ID: " . $this->id . " Title: " . $this->title . " Description: " . $this->description . " Owner_id: " . $this->owner_id . " Members_array " . implode($this->members_array);;            
        }
        return "Group| ID: " . $this->id . " Title: " . $this->title . " Description: " . $this->description . " Owner_id: " . $this->owner_id;
    }

}