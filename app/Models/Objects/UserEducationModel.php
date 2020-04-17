<?php
/**
 * Model | app/Models/Objects/UserEducationModel.php
 * Model for holding user education information
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Objects;
// This model is for containing the user's education information

class UserEducationModel
{

    private $id;

    private $school;
    
    private $degree;
    
    private $years;
    
    private $user_id;
    
    function __construct($id, $school, $degree, $years, $user_id)
    {
        $this->id = $id;
        $this->school = $school;
        $this->degree = $degree;
        $this->years = $years;
        $this->user_id = $user_id;
    }
        
    public function getId()
    {
        return $this->id;
    }

    public function getSchool()
    {
        return $this->school;
    }

    public function getDegree()
    {
        return $this->degree;
    }

    public function getYears()
    {
        return $this->years;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSchool($school)
    {
        $this->school = $school;
    }

    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    public function setYears($years)
    {
        $this->years = $years;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __toString()
    {
        return "UserEducation| ID: " . $this->id . " School: " . $this->school . " Degree: " . $this->degree . " Years: " . $this->years . " User_id " . $this->user_id;
    }

}