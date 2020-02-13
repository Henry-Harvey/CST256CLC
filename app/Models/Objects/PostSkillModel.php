<?php
namespace App\Models\Objects;
//This model is for containing the information for people to log in to the site

// product class
class PostSkillModel
{

    private $id;

    private $skill;
    
    private $post_id;
    
    function __construct($id, $skill, $post_id)
    {
        $this->id = $id;
        $this->skill = $skill;
        $this->post_id = $post_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSkill()
    {
        return $this->skill;
    }

    public function getPost_id()
    {
        return $this->post_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSkill($skill)
    {
        $this->skill = $skill;
    }

    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;
    }

    public function __toString()
    {
        return "PostSkill| ID: " . $this->id . " Skill: " . $this->skill . " Post_id " . $this->post_id;
    }

}