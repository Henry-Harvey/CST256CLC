<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserDataService;
use App\Models\Utility\DatabaseModel;
use App\Models\Utility\Logger;
use App\Models\Services\Data\CredentialsDataService;
use App\Models\Objects\CredentialsModel;

class AccountBusinessService
{

    /**
     * Takes in a user model to be created
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates credentials and user data services
     * Calls the credentials data service create method with the user's credentials
     * If flag is -1, rollback, sets db to null, and returns the flag
     * Sets the user's credentials_id to the flag
     * Calls the user data service create method with the user
     * If the flag2 is not equal to 1, rollback, sets db to null, and returns the flag
     * Commits changes to db and sets db to null
     * Returns flag2
     *
     * @param
     *            newUser user to be registered
     * @return {@link Integer} number of rows affected
     */
    function register($newUser)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUser);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates credentials and user data services
        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        // Calls the credentials data service create method with the user's credentials
        $flag = $credentialsDS->create($newUser->getCredentials());

        // If flag is -1, rollback, sets db to null, and returns the flag
        if ($flag == - 1) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        // Sets the user's credentials_id to the flag
        $insertId = $flag;
        $newUser->setCredentials_id($insertId);

        // Calls the user data service create method with the user
        $flag2 = $userDS->create($newUser);

        // If the flag2 is not equal to 1, rollback, sets db to null, and returns the flag
        if ($flag2 != 1) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns flag2
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag2;
    }

    /**
     * Takes in a user model to be found
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates credentials and user data services
     * Calls the user data service read method with the user
     * If flag is an int, rollback, sets db to null, and returns the flag
     * Creates a credentials model whose id is the found user's credentials_id
     * Calls the credentials data service read method with the new credentials model
     * If flag2 is an int, rollback, sets db to null, and returns the flag
     * Set the user's credentials to the found credentials
     * Commits changes to db and sets db to null
     * Returns found user
     *
     * @param
     *            partialUser user to be found
     * @return {@link UserModel} user that was found
     */
    function getUser($partialUser)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialUser);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates credentials and user data services
        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        // Calls the user data service read method with the user
        $flag = $userDS->read($partialUser);

        // If flag is an int, rollback, sets db to null, and returns the flag
        if (is_int($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        // Creates a credentials model whose id is the found user's credentials_id
        $user = $flag;
        $partialCredentials = new CredentialsModel($user->getCredentials_id(), "", "", 0);

        // Calls the credentials data service read method with the new credentials model
        $flag2 = $credentialsDS->read($partialCredentials);

        // If flag2 is an int, rollback, sets db to null, and returns the flag
        if (is_int($flag2)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        // Set the user's credentials to the found credentials
        $credentials = $flag2;
        $user->setCredentials($credentials);

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns found user
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }

    /**
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates credentials and user data services
     * Calls the user data service readAll method
     * If flag is empty, rollback, sets db to null, and returns the flag
     * For each of the users found
     * Creates a new credentials model whose id is the user's credentials_id
     * Calls the credentials data service read method with the new credentials model
     * If flag2 is an int, rollback, sets db to null, and returns the flag
     * Set the user's credentials to the found credentials
     * After for each
     * Commits changes to db and sets db to null
     * Returns array of found users
     *
     * @return {@link Array} array of users found
     */
    function getAllUsers()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates credentials and user data services
        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        // Calls the user data service readAll method
        $flag = $userDS->readAll();

        // If flag is empty, rollback, sets db to null, and returns the flag
        if (empty($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        // For each of the users found
        $users_array = $flag;
        foreach ($users_array as $user) {
            // Creates a new credentials model whose id is the user's credentials_id
            $partialCredentials = new CredentialsModel($user->getCredentials_id(), "", "", 0);

            // Calls the credentials data service read method with the new credentials model
            $flag2 = $credentialsDS->read($partialCredentials);

            // If flag2 is an int, rollback, sets db to null, and returns the flag
            if (is_int($flag2)) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag2;
            }

            // Set the user's credentials to the found credentials
            $credentials = $flag2;
            $user->setCredentials($credentials);
        }
        // After for each

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns array of found users
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserModel array");
        return $users_array;
    }

    /**
     * Takes in a user model to be updated
     * Creates a new database model and gets the database from it
     * Creates user data service
     * Calls the user data service update method with the user
     * Sets db to null
     * Returns flag
     *
     * @param
     *            updatedUser user to be updated
     * @return {@link Integer} number of rows affected
     */
    function editUser($updatedUser)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedUser);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates user data service
        $userDS = new UserDataService($db);

        // Calls the user data service update method with the user
        $flag = $userDS->update($updatedUser);

        // Sets db to null
        $db = null;

        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }

    /**
     * Takes in a user model to be deleted
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates credentials and user data services
     * Calls the user data service delete method with the user
     * Calls the credentials data service delete method with the user's credentials
     * If both flags equal 1, commit changes and set flag3 equal to 1
     * Else rollback and set flag3 equal to 0
     * Sets db to null
     * Returns flag3
     *
     * @param
     *            deleteUser user to be deleted
     * @return {@link Integer} number of rows affected
     */
    function remove($deleteUser)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $deleteUser);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates credentials and user data services
        $credentialsDS = new CredentialsDataService($db);
        $userDS = new UserDataService($db);

        // Calls the user data service delete method with the user
        $flag = $userDS->delete($deleteUser);

        // Calls the credentials data service delete method with the user's credentials
        $flag2 = $credentialsDS->delete($deleteUser->getCredentials());

        // If both flags equal 1, commit changes and set flag3 equal to 1
        if ($flag == 1 && $flag2 == 1) {
            $db->commit();
            $flag3 = 1;
        } // Else rollback and set flag3 equal to 0
        else {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $flag3 = 0;
        }

        // Sets db to null
        $db = null;

        // Returns flag3
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag3);
        return $flag3;
    }

    /**
     * Takes in a credentials model
     * Creates a new database model and gets the database from it
     * Creates credentials data service
     * Calls the credentials data service authenticate method with the credentials
     * Sets db to null
     * Returns flag
     *
     * @param
     *            loginCredentials credentials to authenticate
     * @return {@link UserModel} user that was authenticated
     */
    function login($loginCredentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $loginCredentials);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates credentials data service
        $ds = new CredentialsDataService($db);

        // Calls the credentials data service authenticate method with the credentials
        $flag = $ds->authenticate($loginCredentials);

        // Sets db to null
        $db = null;

        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }

    /**
     * Takes in a user model to toggle
     * Creates a new database model and gets the database from it
     * Creates a credentials data service
     * Sets credentials equal to the user's credentials
     * If the credentials' suspended is not 0, sets it to 0
     * Else sets suspended to 1
     * Calls the credentials data service update method with the credentials
     * Sets db to null
     * Returns flag
     *
     * @param
     *            searchUser user to be toggled
     * @return {@link Array} number of rows affected
     */
    function toggleSuspension($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Creates a credentials data service
        $ds = new CredentialsDataService($db);

        // Sets credentials equal to the user's credentials
        $credentials = $user->getCredentials();

        // If the credentials' suspended is not 0, sets it to 0
        if ($credentials->getSuspended() != 0) {
            $credentials->setSuspended(0);
        } // Else sets suspended to 1
        else {
            $credentials->setSuspended(1);
        }

        // Calls the credentials data service update method with the credentials
        $flag = $ds->update($credentials);

        // Sets db to null
        $db = null;

        // Returns flag
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}



 
 
