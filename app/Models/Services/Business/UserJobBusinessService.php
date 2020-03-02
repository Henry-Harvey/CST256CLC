<?php
namespace App\Models\Services\Business;

use App\Models\Utility\Logger;
use App\Models\Utility\DatabaseModel;
use App\Models\Objects\UserJobModel;
use App\Models\Services\Data\UserJobDataService;

class UserJobBusinessService
{
    /**
     * Takes in a user job model to be created
     * Creates a new database model and gets the database from it
     * Creates userJob data service and calls create method with the userJob
     * Sets db to null
     * Returns flag
     *
     * @param
     *            newUserJob userJob to be created
     * @return {@link Integer} number of rows affected
     */
    function createJob($newUserJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserJob);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates userJob data service and calls create method with the userJob
        $ds = new UserJobDataService($db);
        // flag is rows affected
        $flag = $ds->create($newUserJob);

        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user job model to be found
     * Creates a new database model and gets the database from it
     * Creates userJob data service and calls read method with the userJob
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialJob userJob to be found
     * @return UserJobModel userJob found
     */
    function getJob($partialJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialJob);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userJob data service and calls read method with the userJob
        $ds = new UserJobDataService($db);        
        // flag is UserJob model or rows found
        $flag = $ds->read($partialJob);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a user to find their job
     * Creates a new database model and gets the database from it
     * Creates userJob data service and calls readAllFor method with the user
     * Sets db to null
     * Returns flag
     *
     * @param
     *            user user to find their job
     * @return    Array array of userJob found
     */
    function getAllJobsForUser($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
       
        // Creates userJob data service and calls readAllFor method with the user
        $ds = new UserJobDataService($db);     
        // flag is array of UserJob models
        $flag = $ds->readAllFor($user);  
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    /**
     * Takes in a userJob to be updated
     * Creates a new database model and gets the database from it
     * Creates userJob data service and calls update method with the userJob
     * Sets db to null
     * Returns flag
     *
     * @param
     *            updatedJob userJob to be updated
     * @return {@link Integer} number of rows affected
     */
    function editJob($updatedJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedJob);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userJob data service and calls update method with the userJob
        $ds = new UserJobDataService($db);
        
        // flag is rows affected
        $flag = $ds->update($updatedJob);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a userJob to be deleted
     * Creates a new database model and gets the database from it
     * Creates userJob data service and calls delete method with the userJob
     * Sets db to null
     * Returns flag
     *
     * @param
     *            partialJob userJob to be deleted
     * @return {@link Integer} number of rows affected
     */
    function remove($partialJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialJob);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates userJob data service and calls delete method with the userJob
        $ds = new UserJobDataService($db);        
        // flag is rows affected
        $flag = $ds->delete($partialJob);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}