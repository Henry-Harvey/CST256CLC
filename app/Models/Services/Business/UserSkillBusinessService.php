<?php
/**
 * Business Service | app/Models/Services/Business/UserSkillBusinessService.php
 * Business Service for dealing with user skill logic
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Business;

use App\Models\Objects\UserSkillModel;
use App\Models\Services\Data\UserSkillDataService;
use App\Models\Utility\DatabaseModel;
use App\Models\Utility\Logger;


class UserSkillBusinessService
{
    /**
     * Takes in a user skill model to be created
     * Creates a new database model and gets the database from it
     * Creates userSkill data service and calls create method with the userSkill
     * Sets db to null
     * Returns flag
     *
     * @param
     *            newUserSkill userSkill to be created
     * @return {@link Integer} number of rows affected
     */
    function createSkill($newUserSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserSkill);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userSkill data service and calls create method with the userSkill
        $ds = new UserSkillDataService($db);        
        // flag is rows affected
        $flag = $ds->create($newUserSkill);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user skill model to be found
     * Creates a new database model and gets the database from it
     * Creates userSkill data service and calls read method with the userSkill
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialSkill userSkill to be found
     * @return UserSkillModel userSkill found
     */
    function getSkill($partialSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialSkill);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userSkill data service and calls read method with the userSkill
        $ds = new UserSkillDataService($db);        
        // flag is UserJob model or rows found
        $flag = $ds->read($partialSkill);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user to find their skill
     * Creates a new database model and gets the database from it
     * Creates userSkill data service and calls readAllFor method with the user
     * Sets db to null
     * Returns flag
     *
     * @param
     *            user user to find their skill
     * @return    Array array of userSkill found
     */
    function getAllSkillsForUser($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userSkill data service and calls readAllFor method with the user
        $ds = new UserSkillDataService($db);       
        // flag is array of UserSkill models
        $flag = $ds->readAllFor($user);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    /**
     * Takes in a userSkill to be updated
     * Creates a new database model and gets the database from it
     * Creates userSkill data service and calls update method with the userSkill
     * Sets db to null
     * Returns flag
     *
     * @param
     *            updatedSkill userSkill to be updated
     * @return {@link Integer} number of rows affected
     */
    function editSkill($updatedSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedSkill);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userSkill data service and calls update method with the userSkill
        $ds = new UserSkillDataService($db);       
        // flag is rows affected
        $flag = $ds->update($updatedSkill);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a userSkill to be deleted
     * Creates a new database model and gets the database from it
     * Creates userSkill data service and calls delete method with the userSkill
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialSkill userSkill to be deleted
     * @return {@link Integer} number of rows affected
     */
    function remove($partialSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialSkill);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userSkill data service and calls delete method with the userSkill
        $ds = new UserSkillDataService($db);       
        // flag is rows affected
        $flag = $ds->delete($partialSkill);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}

