<?php
/**
 * Business Service | app/Models/Services/Business/GroupBusinessService.php
 * Business Service for dealing with group logic
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Business;

use App\Models\Utility\DatabaseModel;
use App\Models\Utility\Logger;
use App\Models\Services\Data\GroupDataService;
use App\Models\Services\Data\Group_Has_UserDataService;
use App\Models\Services\Data\UserDataService;
use App\Models\Objects\UserModel;
use App\Models\Objects\Group_Has_UserModel;

class GroupBusinessService
{

    /**
     * Takes in a Group model to be created
     * Creates a new database model and gets the database from it
     * Creates Group and Group_Has_User data services
     * Calls the Group data service create method with the Group
     * If flag is -1, sets db to null and returns the flag
     * Creates a new Group_has_user with the group's insert id and the group's owner_id
     * Calls the Group_Has_User data service create method with the Group_Has_User
     * If the flag2 is not equal to 1, sets db to null and returns the flag
     * Sets db to null
     * Returns 1
     *
     * @param
     *            newGroup Group to be created
     * @return {@link Integer} number of rows affected
     */
    function createGroup($newGroup)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newGroup);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // should have an atomic transaction, bug with tables
        //$db->beginTransaction();

        // Creates Group and Group_Has_User data services
        $groupDS= new GroupDataService($db);

        // Calls the Group data service create method with the Group
        // flag is insert Id or -1 
        $flag = $groupDS->create($newGroup);

        // If flag is -1, sets db to null and returns the flag
        if ($flag == -1) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            //$db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }
        
        $group_id = $flag;
        
        // Creates a new Group_has_user with the group's insert id and the group's owner_id
        $group_has_users = new Group_Has_UserModel($group_id, $newGroup->getOwner_id());
        
        // Calls the Group_Has_User data service create method with the Group_Has_User
        $flag2 = $this->join($group_has_users);
        
        // If the flag2 is not equal to 1, sets db to null and returns the flag
        if($flag2 != 1){
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            //$db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }
        
        //$db->commit();
        
        // Sets db to null
        $db = null;
        
        // Returns 1
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag2;
    }

    /**
     * Takes in a partial Group model to be found
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates Group and Group_Has_User data services
     * Calls the Group data service read method with the user
     * If flag is an int, rollback, sets db to null, and returns the flag
     * Calls the Group_Has_User data service readAllFor method with the new Group model
     * If flag2 is an int, rollback, sets db to null, and returns the flag
     * Set the Group's Group_Has_Users to the found Group_Has_Users
     * Commits changes to db and sets db to null
     * Returns found Group
     *
     * @param
     *            partialGroup Group to be found
     * @return {@link GroupModel} Group that was found
     */
    function getGroup($partialGroup)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialGroup);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates Group and Group_Has_User data services
        $groupDS = new GroupDataService($db);
        $group_Has_UserDS = new Group_Has_UserDataService($db);
        $userDS = new UserDataService($db);

        // Calls the Group data service read method with the user
        // flag is GroupModel or rows found
        $flag = $groupDS->read($partialGroup);

        // If flag is an int, rollback, sets db to null, and returns the flag
        if (is_int($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $group = $flag;

        // Calls the Group_Has_User data service readAllFor method with the new Group model
        $flag2 = $group_Has_UserDS->readAllFor($group);

        // If flag2 is an int, rollback, sets db to null, and returns the flag
        if (empty($flag2)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with -1");
            return -1;
        }

        $Group_Has_User_array = $flag2;
        $members_array = array();
        foreach ($Group_Has_User_array as $group_has_user){
            $partialUser = new UserModel($group_has_user->getUser_id(), "", "", "", "", 0, 0);            
            $flag3 = $userDS->read($partialUser);
            
            if (is_int($flag3)) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag3);
                return $flag3;
            }
            
            $user = $flag3;
            array_push($members_array, $user);
        }
        
        // Set the Group's Group_Has_Users to the found Group_Has_Users
        $group->setMembers_array($members_array);

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns found Group
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        return $group;
    }

    /**
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates Group and Group_Has_User data services
     * Calls the Group data service readAll method
     * If flag is empty, rollback, sets db to null, and returns the flag
     * For each of the Groups found
     * Calls the Group_Has_User data service readAllFor method with the Group
     * If flag2 is empty, rollback, sets db to null, and returns the flag
     * Set the Group's Group_Has_Users to the Group_Has_Users found
     * After for each
     * Commits changes to db and sets db to null
     * Returns array of found Groups
     *
     * @return {@link Array} array of Groups found
     */
    function getAllGroups()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates Group and Group_Has_User data services
        $groupDS = new GroupDataService($db);
        $group_Has_UserDS = new Group_Has_UserDataService($db);
        $userDS = new UserDataService($db);

        // Calls the Group data service readAll method
        // flag is array Group models
        $flag = $groupDS->readAll();

        // If flag is empty, rollback, sets db to null, and returns the flag
        if (empty($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
            return $flag;
        }

        // For each of the Groups found
        $Groups_array = $flag;
        foreach ($Groups_array as $group) {
            // Calls the Group_Has_User data service readAllFor method with the Group
            // flag2 is array of Group_Has_User models
            $flag2 = $group_Has_UserDS->readAllFor($group);

            // If flag2 is empty, rollback, sets db to null, and returns the flag
            if (empty($flag2)) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag2));
                return $flag2;
            }

            // Set the Group's Group_Has_Users to the Group_Has_Users found
            $Group_Has_User_array = $flag2;
            $members_array = array();
            foreach ($Group_Has_User_array as $group_has_user){
                $partialUser = new UserModel($group_has_user->getUser_id(), "", "", "", "", 0, 0);
                $flag3 = $userDS->read($partialUser);
                
                if (is_int($flag3)) {
                    Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                    $db->rollBack();
                    $db = null;
                    Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag3);
                    return $flag3;
                }
                
                $user = $flag3;
                array_push($members_array, $user);
            }
            
            // Set the Group's Group_Has_Users to the found Group_Has_Users
            $group->setMembers_array($members_array);
        }
        // After for each

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns array of found Groups
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with GroupModel array");
        return $Groups_array;
    }

    /**
     * Takes in a Group model to be updated
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates Group and Group_Has_User data services
     * Calls the Group data service update method with the Group
     * Calls the Group_Has_User data service deleteAllFor method with the Group
     * For each of the Group's Group_Has_Users
     * Calls the Group_Has_User data service create method with the Group_Has_User
     * If flag3 is not equal to 1, rollback, sets db to null, and returns the flag
     * End for each
     * If flag and flag2 both equal 0, rollback, sets db to null, and returns 0
     * Commits changes to db and sets db to null
     * Returns 1
     *
     * @param
     *            updatedGroup Group to be updated
     * @return {@link Integer} number of rows affected
     */
    function editGroup($updatedGroup)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedGroup);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates Group and Group_Has_User data services
        $ds = new GroupDataService($db);

        // Calls the Group data service update method with the Group
        // flag is rows affected, Okay if not affected
        $flag = $ds->update($updatedGroup);      
             
        // If flag and flag2 both equal 0, rollback, sets db to null, and returns 0
        if($flag == 0){
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 0");
            return 0;
        }
 
        $db = null;
        // Returns 1
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }

    /**
     * Takes in a partial Group model to be deleted
     * Creates a new database model and gets the database from it
     * Creates Group data service
     * Calls the Group data service delete method with the Group
     * If flag is not 1, sets db to null and returns the flag
     * Returns flag
     *
     * @param
     *            partialGroup Group to be deleted
     * @return {@link Integer} number of rows affected
     */
    function removeGroup($partialGroup)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialGroup);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates Group data service
        $ds = new GroupDataService($db);

        // Calls the Group data service delete method with the Group
        // flag is rows affected
        $flag = $ds->delete($partialGroup);

        // If flag is not 1, sets db to null and returns the flag
        if ($flag != 1) {
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $db = null;

        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    /**
     * Takes in a group_has_user model
     * Creates a new database model and gets the database from it
     * Creates Group_has_user data service
     * Calls the Group_has_user data service read method with the Group_has_user
     * If flag is not not an int, sets db to null and returns the flag
     * Calls the Group_has_user data service create method with the Group_has_user
     * Sets db to null
     * Returns flag2
     *
     * @param
     *            group_has_user Group_has_user to be created
     * @return {@link Integer} number of rows affected
     */
    function join($group_has_user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group_has_user);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates Group_has_user data service
        $ds = new Group_Has_UserDataService($db);
        
        // Calls the Group_has_user data service read method with the Group_has_user
        $flag = $ds->read($group_has_user);
        
        // If flag is not not an int, sets db to null and returns the flag
        if(!is_int($flag)){
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with -1");
            return -1;
        }
        
        // Calls the Group_has_user data service create method with the Group_has_user
        $flag2 = $ds->create($group_has_user);
        
        // Sets db to null
        $db = null;
        
        // Returns flag2
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag2;
    }
    
    /**
     * Takes in a group_has_user model
     * Creates a new database model and gets the database from it
     * Creates Group_has_user data service
     * Calls the Group_has_user data service delete method with the Group_has_user
     * Sets db to null
     * Returns flag
     *
     * @param
     *            group_has_user Group_has_user to be deleted
     * @return {@link Integer} number of rows affected
     */
    function leave($group_has_user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group_has_user);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Creates Group_has_user data service
        $ds = new Group_Has_UserDataService($db);      
        
        // Calls the Group_has_user data service delete method with the Group_has_user
        $flag = $ds->delete($group_has_user);
        
        // Sets db to null
        $db = null;
        
        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}



 
 
