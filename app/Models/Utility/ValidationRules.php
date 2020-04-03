<?php
namespace App\Models\Utility;
// this class contains the rules for form validation

class ValidationRules
{

    public function getRegistrationRules()
    {
        $rules = [
            'username' => 'Required | Between:1,50',
            'password' => 'Required | Between:1,50',
            'firstname' => 'Required | Between:1,50',
            'lastname' => 'Required | Between:1,50',
            'location' => 'Required | Between:1,50',
            'summary' => 'Required | Between:1,50'
        ];
        return $rules;
    }

    public function getLoginRules()
    {
        $rules = [
            'username' => 'Required | Between:1,50',
            'password' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    public function getProfileEditRules()
    {
        $rules = [
            'id' => 'Required',
            'firstname' => 'Required | Between:1,50',
            'lastname' => 'Required | Between:1,50',
            'location' => 'Required | Between:1,50',
            'summary' => 'Required | Between:1,50',
            'role' => 'Required',
            'credentials_id' => 'Required'
        ];
        return $rules;
    }
    
    public function getPostRules()
    {
        $rules = [
            'title' => 'Required | Between:1,50',
            'company' => 'Required | Between:1,50',
            'location' => 'Required | Between:1,50',
            'description' => 'Required | Between:1,75',
            'skill1' => 'Required | Between:1,50',
        ];
        return $rules;
    }
    
    public function getUserJobRules()
    {
        $rules = [
            'title' => 'Required | Between:1,50',
            'company' => 'Required | Between:1,50',
            'years' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    public function getUserSkillRules()
    {
        $rules = [
            'skill' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    
    public function getUserEducationRules()
    {
        $rules = [
            'school' => 'Required | Between:1,50',
            'degree' => 'Required | Between:1,50',
            'years' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    public function getGroupRules()
    {
        $rules = [
            'title' => 'Required | Between:1,50',
            'description' => 'Required | Between:1,75'
        ];
        return $rules;
    }
    
     
    public function getSearchRules()
    {
        $rules = [
            
            'title' => 'Required | Between:1,50',
            'description' => 'Required | Between:1,75'
            
        ];
        return $rules;
    }
    
}

