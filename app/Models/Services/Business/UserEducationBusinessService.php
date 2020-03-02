<?php
namespace App\Models\Services\Business;

use App\Models\Utility\Logger;
use App\Models\Objects\UserEducationModel;
use App\Models\Services\Data\UserEducationDataService;
use App\Models\Utility\DatabaseModel;


class UserEducationBusinessService
{
    /**
     * Takes in a user education model to be created
     * Creates a new database model and gets the database from it
     * Creates userEducation data service and calls create method with the userEducation
     * Sets db to null
     * Returns flag
     *
     * @param
     *            newUserEducation userEducation to be created
     * @return {@link Integer} number of rows affected
     */
    function createEducation($newUserEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserEducation);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates userEducation data service and calls create method with the userEducation
        $ds = new UserEducationDataService($db);
        // flag is rows affected
        $flag = $ds->create($newUserEducation);

        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user education model to be found
     * Creates a new database model and gets the database from it
     * Creates userEducation data service and calls read method with the userEducation
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialEducation userEducation to be found
     * @return UserEducationModel userEducation found
     */
    function getEducation($partialEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialEducation);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userEducation data service and calls read method with the userEducation
        $ds = new UserEducationDataService($db);       
        // flag is UserEducation model or rows found
        $flag = $ds->read($partialEducation);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user to find their education
     * Creates a new database model and gets the database from it
     * Creates userEducation data service and calls readAllFor method with the user
     * Sets db to null
     * Returns flag
     *
     * @param
     *            user user to find their education
     * @return    Array array of userEducation found
     */
    function getAllEducationForUser($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
       
        // Creates userEducation data service and calls readAllFor method with the user
        $ds = new UserEducationDataService($db);     
        // flag is array of UserEducation models
        $flag = $ds->readAllFor($user);       
        
        // Sets db to null
        $db = null;
        
        //Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    /**
     * Takes in a userEducation to be updated
     * Creates a new database model and gets the database from it
     * Creates userEducation data service and calls update method with the userEducation
     * Sets db to null
     * Returns flag
     *
     * @param
     *            updatedEducation userEducation to be updated
     * @return {@link Integer} number of rows affected
     */
    function editEducation($updatedEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedEducation);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates userEducation data service and calls update method with the userEducation
        $ds = new UserEducationDataService($db);       
        // flag is rows affected
        $flag = $ds->update($updatedEducation);           
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a userEducation to be deleted
     * Creates a new database model and gets the database from it
     * Creates userEducation data service and calls delete method with the userEducation
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialEducation userEducation to be deleted
     * @return {@link Integer} number of rows affected
     */
    function remove($partialEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialEducation);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userEducation data service and calls delete method with the userEducation
        $ds = new UserEducationDataService($db);        
        // flag is rows affected
        $flag = $ds->delete($partialEducation);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}

