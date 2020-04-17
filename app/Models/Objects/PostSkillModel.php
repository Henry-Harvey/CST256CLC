<?php
/**
 * Model | app/Models/Objects/PostSkillModel.php
 * Model for holding job posting skill information
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Objects;
// This model is for containing a job post's skill information

class PostSkillModel implements \JsonSerializable
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
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}